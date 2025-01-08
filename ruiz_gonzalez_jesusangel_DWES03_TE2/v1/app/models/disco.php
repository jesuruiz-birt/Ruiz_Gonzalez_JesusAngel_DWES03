<?php

class Disco
{
    public $id;
    public $name;
    public $artist;
    public $type_of_music;
    public $release_date;

    public function __construct($id, $name, $artist, $type_of_music, $release_date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->artist = $artist;
        $this->type_of_music = $type_of_music;
        $this->release_date = $release_date;
    }

    public static function getAll()
    {
        $data = file_get_contents(__DIR__ . '/../../data/discos.json');
        $discosArray = json_decode($data, true);

        $discos = [];
        foreach ($discosArray as $discoData) {
            $discos[] = new Disco(
                $discoData['id'],
                $discoData['name'],
                $discoData['artist'],
                $discoData['type_of_music'],
                $discoData['release_date']
            );
        }
        return $discos;
    }

    public static function getById($id)
    {
        $discos = self::getAll();
        foreach ($discos as $disco) {
            if ($disco->id == $id) {
                return $disco;
            }
        }
        return null;
    }

    public static function saveAll($discos)
    {
        $discosArray = array_map(fn($disco) => get_object_vars($disco), $discos);
        file_put_contents(__DIR__ . '/../../data/discos.json', json_encode($discosArray));
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}