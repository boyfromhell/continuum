<?php

function create($class, $attributes = [], $times = null)
{
    $model = 'App\\Models\\' . $class;
    if (isset($times)) {
        return $model::factory()->count($times)->create($attributes);
    } else {
        return $model::factory()->create($attributes);
    }
}

function make($class, $attributes = [], $times = null)
{
    $model = 'App\\Models\\' . $class;
    if (isset($times)) {
        return $model::factory()->count($times)->make($attributes);
    } else {
        return $model::factory()->make($attributes);
    }
}
