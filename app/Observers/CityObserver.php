<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Observer;

use App\Models\City;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class CityObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  City $city
     * @return void
     */
    public function deleting(City $city)
    {
        // Get Posts
        $posts = Post::where('city_id', $city->id)->get();
        if ($posts->count() > 0) {
            foreach($posts as $post) {
                $post->delete();
            }
        }
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  City $city
     * @return void
     */
    public function updated(City $city)
    {
        // Update all the City's Posts
        $posts = Post::where('city_id', $city->id)->get();
        if ($posts->count() > 0) {
            foreach($posts as $post) {
                $post->lon = $city->longitude;
                $post->lat = $city->latitude;
                $post->save();
            }
        }
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  City $city
     * @return void
     */
    public function saved(City $city)
    {
        // Removing Entries from the Cache
        $this->clearCache($city);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  City $city
     * @return void
     */
    public function deleted(City $city)
    {
        // Removing Entries from the Cache
        $this->clearCache($city);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $city
     */
    private function clearCache($city)
    {
        Cache::flush();
    }
}