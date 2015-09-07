<?php

namespace Modules\Product\Entities;

class Product extends Model
{
 
    /**
     * 
     *
     */
    protected $table = 'product';
    
    
    /**
     * Query
     *
     * @return 
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', '=', 1);   
    }
    
    
    /**
     * Return attributes collection of given product
     *
     * @return 
     */
    public function attributes()
    {
        return $this->hasMany('Modules\Product\Entities\ProductAttribute');   
    }
    
    /**
     * Return options collection of given product
     * 
     * @return
     */
    public function options()
    {
        return $this->hasMany('Modules\Product\Entities\ProductOptions');   
    }
    
}