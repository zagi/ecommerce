<?php

namespace Modules\Cart\Repositories;

use Modules\Cart\Exceptions\InvalidConditionException;
use Modules\Cart\Exceptions\InvalidItemException;
use Modules\Cart\Helpers\Helper;
use Modules\Cart\Validators\CartItemValidator;

class Cart
{
    
    protected $session;
    
    protected $events;
    
    protected $instanceName;
    
    protected $sessionKeyCartItems;
    
    protected $sessionKeyCartConditions;
    
    public function __construct($session, $events, $instanceName, $session_key)
    {
        $this->session      = $session;
        $this->events       = $events;
        $this->instanceName = $instanceName;
        $this->sessionKeyCartItems = $session_key.'_cart_items';
        $this->sessionKeyCartConditions = $session_key.'_cart_conditions';
        $this->events->fire($this->getInstanceName().'.created', [$this]);
    }
    
    public function getInstanceName()
    {
        return $this->instanceName;   
    }
    
    public function get($itemId)
    {
        return $this->getContent()->get($itemId);   
    }
    
    public function has($itemId)
    {
        return $this->getContent()->has($itemId);
    }
    
    public function add($id, $name = null, $price = null, $quantity = null, $attributes = [], $conditions = [])
    {
        if( is_array($id) )
        {
            if( Helper::isMultiArray($id) )
            {
                foreach($id as $item)
                {
                    $this->add(
                        $item['id'],
                        $item['name'],
                        $item['price'],
                        $item['quantity'],
                        Helper::issetAndHasValueOrAssignDefault($item['attributes'], []),
                        Helper::issetAndHasValueOrAssignDefault($item['conditions'], [])
                    );
                }
            } else {
                $this->add(
                    $id['id'],
                    $id['name'],ItemAttributeCollectio
                    $id['price'],
                    $id['quantity'],
                    Helper::issetAndHasValueOrAssignDefault($item['attributes'], []),
                    Helper::issetAndHasValueOrAssignDefault($item['conditions'], [])
                );
            //return object to chain
            return $this;    
            }       
        }
        
        //validate data
        $item = $this->validate([
            'id'        => $id,
            'name'      => $name,
            'price'     => Helper::normalizePrice($price),
            'quantity'  => $quantity,
            'attributes'=> new ItemAttributeCollection($attributes),
            'conditions'=> $conditions
        ]);
        
        $cart = $this->getContent();
        
        if( $cart->has($id) )
        {
            $this->events->fire($this->getInstanceName().'.updating', [$item, $this]);
            $this->update($id, $item);
            $this->events->fire($this->getInstanceName().'.updated', [$item, $this]);
        } else {
            $this->events->fire($this->getInstanceName().'.adding', [$item, $this]);
            $this->addRow($id, $item);
            $this->events->fire($this->getInstanceName().'.added', [$item, $this]);
        }
        
        return $this;
    }
    
    public function update($id, $data)
    {
        $cart = $this->getContent();
        
        $item = $cart->pull($id);
        
        foreach($data as $key => $value)
        {
            //if key is quantity we need to check if a symbol is present
            //than check if the update is plus(add) or minus(reduce)
            if( $key = "quantity" )
            {
                if( preg_match('/\-/', $value) == 1 )
                {
                    $value = (int) str_replace('-', '', $value);
                    
                    if( ($item[$key] - $value) > 0 )
                    {
                        $item[$key] -= $value;   
                    }
                } elseif( preg_match('/\+/', $value) == 1 ) {
                    $item[$key] += (int) str_replace('+', '',$value);
                } else {
                    $item[$key] += (int) $value;
                }
            
            } else {
                $item[$key] = $value;   
            }
        }
        
        $cart->put($id, $item);
        $this->save($cart);
    }
    
    public function addItemCondition($productId, $itemCondition)
    {
        if( $product = $this->get($productId) )
        {
            $itemConditionTempHolder = $product['conditions'];
            
            if( is_array($itemConditionTempHolder) )
            {
                array_push($itemConditionTempHolder, $itemCondition);
            } else {
                $itemConditionTempHolder = $itemCondition;
            }
            
            $this->update($productId, [
                'conditions' => $itemConditionTempHolder
            ]);
            
            return $this;
    }
        
    public function remove($id)
    {
        $cart = $this->getContent();
            
        $this->events->fire($this->getInstanceName().'removing', [$id, $this]);
        
        $cart->forget($id);
        $cart->save($cart);
        
        $this->events->fire($this->getInstanceName().'removed', [$id, $this]);
        
    }
        
    public function clear()
    {
        $this->events->fire($this->getInstanceName().'clearing', [ $this]);

        $this->session->put($this->sessionKeyCartItems, []);
        
        $this->events->fire($this->getInstanceName().'cleared', [ $this]);
        
    }
        
    public function condition($condition)
    {
        if( is_array($condition) )
        {
            foreach($condition as $c) {
                $this->condition($c);   
            }    
            return $this;
        }
        
        if( ! $condition instanceof CartCondition )
            throw new InvalidConditionException('Argument 1 must be an instance of CartCondition');
        
        $conditions = $this->getConditions();
        $conditions->put($condition->getName(), $condition);
        $this->saveConditions($conditions);
        
        return $this;
        
    }
        
    public function getConditions()
    {
        return new CartConditionCollection($this->session->get($this->sessionKeyCartConditions));
    }   
        
    public function getCondition($conditionName)
    {
        return $this->getConditions()->get($conditionName);
    }
        
    public function getConditionsByType($type)
    {
        return $this->getConditions()->filter(function(CartCondition $condition) use ($type)
        {
            return $condition->getType() == $type;
        });
    }
        
    public function removeConditionsByType($type)
    {
        $this->getConditionsByType($type)->each(function($condition)
        {
            $this->removeCartCondition($condition->getName());
        });
    }
        
    public function removeCartCondition($conditionName)
    {
        $conditions = $this->getConditions();
        $conditions->pull($conditionName);
        $this->saveConditions($conditions);
    }   
        
    public function removeItemCondition()
    {
        
    }
    
}