<?php

class Advisor_Selector_Keyword 
{
    protected static function getKey($query)
    {
        $key = substr( $query, 0, 1);
        $clean_key = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $key ));

        return ( strlen($clean_key) > 0 ? $clean_key : '0' );
    }

    private static function cleanQuery($name)
    {
        $str = str_replace(array("\t", "\n", "\r"), " ", $name);
        return strtolower(htmlentities(trim($str), ENT_QUOTES, "UTF-8"));
    }

    private static function query( $query )
    {
        $key = self::getKey($query);
        $word = self::cleanQuery($query);
        $q_length = strlen($word);
        
        //$keywordSrcUrl = Url::render('/assets/data/keywords');
        $keywordSrcUrl = 'http://jwhite3854.com/advisor/assets/data/keywords';
        $keywordSrcUrl .= '/' . $key . '.txt';

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $keywordSrcUrl); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $response = curl_exec($ch); 
        curl_close($ch); 
        
        $json_keywords = '{'.rtrim($response,",").'}';
        $keywords = json_decode( $json_keywords, true);
        
        $results = array();
        foreach ( $keywords as $id => $keyword ) {
            $term = substr($keyword, 0, $q_length);
            if ( $word == $term ) {
                $results[] = array(
                    'id' => $id,
                    'text' => ucwords($keyword)
                );
            }
        }

        return $results;
    }

    public static function suggest( $query )
    {
        $results = self::query( $query );

        $suggest = array(
            'results' => $results,
            'pagination' => array(
                "more" => false
            )
        );

       return $suggest;
    }
}



class Advisor_Selector_Person
{
    private static function getKey($query)
    {
        $key = substr( $query, 0, 2);
        $clean_key = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $key ));

        return ( strlen($clean_key) > 0 ? $clean_key : '00' );
    }

    private static function cleanQuery($name)
    {
        $str = str_replace(array("\t", "\n", "\r"), " ", $name);
        return strtolower(htmlentities(trim($str), ENT_QUOTES, "UTF-8"));
    }

    private static function query( $query )
    {
        $key = self::getKey($query);
        $word = self::cleanQuery($query);
        $q_length = strlen($word);
        
        //$keywordSrcUrl = Url::render('/assets/data/people');
        $keywordSrcUrl = 'http://jwhite3854.com/advisor/assets/data/people';
        $keywordSrcUrl .= '/' . $key . '.txt';

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $keywordSrcUrl); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $response = curl_exec($ch); 
        curl_close($ch); 
        
        $json_people = '{'.rtrim($response,",").'}';
        $persons = json_decode( $json_people, true);
        
        $results = array();
        foreach ( $persons as $id => $person ) {
            $term = substr($person, 0, $q_length);
            if ( $word == $term ) {
                $results[] = array(
                    'id' => $id,
                    'text' => ucwords($person)
                );
            }
        }

        return $results;
    }

    public static function suggest( $query )
    {
        $results = self::query( $query );
        $suggest = array(
            'results' => $results,
            'pagination' => array(
                "more" => false
            )
        );

       return $suggest;
    }
}