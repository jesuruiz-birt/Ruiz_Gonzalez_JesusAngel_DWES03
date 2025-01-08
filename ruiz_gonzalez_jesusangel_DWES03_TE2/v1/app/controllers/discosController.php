<?php

require_once __DIR__ . '/../models/disco.php';

class DiscosController
{
    public function getAll() {
        $discos = Disco::getAll();
        $discosArray = array_map(fn($disco) => $disco->toArray(), $discos);

        header('Content-Type: application/json');
        echo json_encode($discosArray);
    }

    public function getById($id) {
        $disco = Disco::getById($id);

        header('Content-Type: application/json');
        if ($disco) {
            echo json_encode($disco->toArray());
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Disco no encontrado']);
        }
    }

    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos no validos']);
            return;
        }

        $discos = Disco::getAll();
        $id = end($discos)->id + 1;
        $newDisco = new Disco($id, $data['name'], $data['artist'], $data['type_of_music'], $data['release_date']);
        $discos[] = $newDisco;

        Disco::saveAll($discos);

        header('Content-Type: application/json');
        echo json_encode($newDisco->toArray());
    }

    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos no válidos']);
            return;
        }

        $discos = Disco::getAll();
        foreach ($discos as $disco) {
            if ($disco->id == $id) {
                $disco->name = $data['name'];
                $disco->artist = $data['artist'];
                $disco->type_of_music = $data['type_of_music'];
                $disco->release_date = $data['release_date'];
                Disco::saveAll($discos);

                header('Content-Type: application/json');
                echo json_encode($disco->toArray());
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Disco no encontrado']);
    }

    public function delete($id) {
        $discos = Disco::getAll();
        $filteredDiscos = array_filter($discos, fn($disco) => $disco->id != $id);

        if (count($discos) == count($filteredDiscos)) {
            http_response_code(404);
            echo json_encode(['error' => 'Disco no encontrado']);
            return;
        }

        Disco::saveAll($filteredDiscos);

        header('Content-Type: application/json');
        echo json_encode(['success' => 'Disco eliminado']);
    }
}
