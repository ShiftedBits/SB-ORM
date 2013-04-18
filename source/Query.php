<?php
interface Query
{
    public function get();
    public function post($data);
    public function put($id, $data);
    public function delete($id);

    public function getAll();
    public function postMany($data);
    public function putMany($ids, $data);
    public function deleteMany($ids);

    public function getById($id);
    public function getByIds($ids);
    public function getByColumn($column, $value);

    public function run();
}