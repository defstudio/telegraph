<?php

namespace DefStudio\Telegraph\DTO;

class Invoice
{
    public string $payload = 'telegraph invoice';
    public function __construct(
        public string $title,
        public string $description,
    ) {
    }
}
