<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface RESTOperation
{
    public function list();

    public function show($id);

    public function create(Request $request);
    
    public function edit($id, Request $request);

    public function delete($id);
}