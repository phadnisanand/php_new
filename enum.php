<?php
// https://stitcher.io/blog/php-enums
// https://stitcher.io/blog/new-in-php-81
enum Status
{
    case DRAFT;
    case PUBLISHED;
    case ARCHIVED;
    
    public function color(): string
    {
        return match($this) 
        {
            Status::DRAFT => 'grey',   
            Status::PUBLISHED => 'green',   
            Status::ARCHIVED => 'red',   
        };
    }
}
$status = Status::PUBLISHED;

echo $status->color(); // 'red'
