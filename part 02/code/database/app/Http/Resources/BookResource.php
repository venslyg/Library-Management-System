<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{

    //@return array<string, mixed>
    public function toArray(Request $request): array
    {
        return [
            // Renaming database fields
            'id' => $this->id,
            'title' => $this->BookName,
            'isbn' => $this->ISBN,
            'publish_year' => $this->PublishYear,
            'stock_available' => $this->NoOfBooks,

            // Nested resource output for the author relationship
            'author' => [
                'id' => $this->author->id ?? null,
                'name' => $this->author->AuthorName ?? 'Unknown',
            ],

            // Adding a timestamp for auditing
            'created_at' => $this->created_at,
        ];
    }
}
