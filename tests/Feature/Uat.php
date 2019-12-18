<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Uat extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNthDigit()
    {
        // post request
        $response = $this->post('/api/find-nth-digit', ['number' => 1000000000]);

        // data
        $data = json_decode($response->getContent());

        // response
        $response->assertStatus(200);

        // json structure
        $response->assertJsonStructure((array) [
            'execution_time',
            'data'
        ]);

        // is integer
        $this->assertEquals(is_int($data->data), true);
    }

    public function testTextAnalyzer()
    {
        // post request
        $response = $this->post('/api/generate-text-info', ['text' => 'Laravel also provides several helpers for testing JSON APIs and their responses.']);

        // data
        $data = json_decode($response->getContent());

        // response
        $response->assertStatus(200);

        // json structure
        $response->assertJsonStructure((array) [
            'execution_time',
            'data' => [
                [
                    'character',
                    'count',
                    'max_distance',
                    'index_position',
                    'siblings' => [
                        'before',
                        'after'
                    ]
                ]
            ]
        ]);

        // is array data
        $this->assertEquals(is_array($data->data), true);

        // assert equal data type
        foreach ($data->data as $key => $row) {
            $this->assertEquals(is_string($row->character), true);
            $this->assertEquals(is_int($row->count), true);
            $this->assertEquals(is_int($row->max_distance), true);
            $this->assertEquals(is_array($row->index_position), true);
            $this->assertEquals(is_array($row->siblings->before), true);
            $this->assertEquals(is_array($row->siblings->after), true);

            // // check if max distance is no more than 10 if necessary
            // $this->assertEquals(($row->max_distance <= 10,) true, 'check if max distance is no more than 10 if necessary');
        }
    }
}
