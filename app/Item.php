<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    use \Dimsav\Translatable\Translatable;

    protected $connection = 'mysql_webumenia';
    public $incrementing = false;

    public $translatedAttributes = [
        'title',
        'description',
        'work_type',
        'work_level',
        'topic',
        'subject',
        'measurement',
        'dating',
        'medium',
        'technique',
        'inscription',
        'place',
        'state_edition',
        'gallery',
        'relationship_type',
        'related_work'
    ];


    // public function authorities()
    // {
    //     return $this->belongsToMany(\App\Authority::class, 'authority_item', 'item_id', 'authority_id')->withPivot('role');
    // }

    public function getAuthorsAttribute($value)
    {
        $authors_array = $this->makeArray($this->author);
        $authors = array();
        foreach ($authors_array as $author) {
            $authors[$author] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author);
        }
        return $authors;
    }

    public function getAuthorFormated($value)
    {
        return formatName($this->attributes['author']);
    }

    public function getFirstAuthorAttribute($value)
    {
        $authors_array = $this->makeArray($this->author);
        return reset($authors_array);
    }

    public function getSubjectsAttribute($value)
    {
        $subjects_array = $this->makeArray($this->subject);
        return $subjects_array;
    }

    public function getTopicsAttribute($value)
    {
        return $this->makeArray($this->topic);
    }

    public function getMediumsAttribute($value)
    {
        return $this->makeArray($this->medium);
    }

    public function getTechniquesAttribute($value)
    {
        return $this->makeArray($this->technique);
    }

    public function getMeasurementsAttribute($value)
    {
        $trans = array("; " => ";", "()" => "");
        return explode(';', strtr($this->measurement, $trans));

        // $measurements_array = explode(';', $this->measurement);
        // $measurements = array();
        // $measurements[0] = array();
        // $i = -1;
        // if (!empty($this->measurement)) {
        // 	foreach ($measurements_array as $key=>$measurement) {
        // 		if ($key%2 == 0) {
        // 			$i++;
        // 			$measurements[$i] = array();
        // 		}
        //      if (!empty($measurement)) {
        // 			$measurement = explode(' ', $measurement, 2);
        // 			if (isSet($measurement[1])) {
        // 				$measurements[$i][$measurement[0]] = $measurement[1];
        // 			} else {
        // 				$measurements[$i][] = $measurement[0];
        // 			}

        // 		}
        // 	}
        // }
        // return $measurements;
    }

    public function getWidthAttribute($value)
    {
        return $this->getMeasurementForDimension('šírka');
    }

    public function getHeightAttribute($value)
    {
        return $this->getMeasurementForDimension('výška');
    }

    private function getMeasurementForDimension($dimension)
    {
        $value = null;
        $trans = array("; " => ";", ", " => ";", "()" => "");
        $measurements =  explode(';', strtr($this->measurement, $trans));
        foreach ($measurements as $measurement) {
            if (str_contains($measurement, $dimension)) {
                $value = preg_replace("/[^0-9\.]/", "", $measurement);
            }
        }
        return $value;
    }

    public function getDatingFormated()
    {
        $count_digits = preg_match_all("/[0-9]/", $this->dating);
        if (($count_digits<2) && !empty($this->date_earliest)) {
            $formated = $this->date_earliest;
            if (!empty($this->date_latest) && $this->date_latest!=$this->date_earliest) {
                $formated .= "&ndash;" . $this->date_latest;
            }
            return $formated;
        }
        $trans = array("/" => "&ndash;", "-" => "&ndash;");
        $formated = preg_replace('/^([0-9]*) \s*([a-zA-Z]*)$/', '$2 $1', $this->dating);
        $parts = explode('/', $formated);
        $formated = implode('/', array_unique($parts));
        $formated = strtr($formated, $trans);
        return $formated;
    }

    public function getWorkTypesAttribute()
    {
        return $this->makeArray($this->work_type, ', ');
    }

    public function makeArray($str, $delimiter = '; ')
    {
        if (is_array($str)) {
            return $str;
        }
        $str = trim($str);
        return (empty($str)) ? array() : explode($delimiter, $str);
    }

    public function getZoomableImages()
    {
        return $this->images->filter(function (ItemImage $image) {
            return $image->isZoomable();
        });
    }

    public function hasZoomableImages() {
        return !$this->getZoomableImages()->isEmpty();
    }

    // alias for preserving backward compatibility
    public function getHasIipAttribute() {
        return $this->hasZoomableImages();
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class)->orderBy('order');
    }

    public function getTitleWithAuthors($html = false)
    {
        $dash = ($html) ? ' &ndash; ' : ' - ';
        return implode(', ', $this->authors)  . $dash .  $this->title;
    }

    public function getUrl($full = true, $params = [])
    {
        $url = 'www.webumenia.sk/dielo/' . $this->id;
        if ($params) {
            $url .= '?' . http_build_query($params);
        }
        if ($full) {
            $url = 'https://' . $url;
        }
        return $url;
    }


}
