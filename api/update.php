<?php
require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if($method === 'put') {

    parse_str(file_get_contents('php://input'), $input);

    //$id =(!empty($input['id'])) ? $input['id'] : null; /* método em php abaixo do 7.4 */
    $id =$input['id'] ?? null;//método no php 7.4
    $title =$input['title'] ?? null;
    $body =$input['body'] ?? null;

    $id = filter_var($id);
    $title =filter_var($title);
    $body = filter_var($body);

    if($id && $title && $body) {

        $sql = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() >0) {

            $sql = $pdo->prepare("UPDATE notes SET title = :title, body =:body WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->bindValue(':title', $title);
            $sql->bindValue(':body', $body);
            $sql->execute();

            $array['result'] = [
                'id' => $id,
                'title' => $title,
                'body' => $body
            ];


        } else {
            $array['error'] = 'ID inexistente';
        }
    } else {
        $array['error'] = 'Dados nao enviados';
    }

      

} else {
    $array['error'] = ' Metodo nao permitido (Apenas PUT)';
}

require('../return.php');