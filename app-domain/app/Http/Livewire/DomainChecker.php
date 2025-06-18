<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class DomainChecker extends Component
{
    public $input = '';
    public $results = [];

    public function checkDomains()
    {
        $this->validate([
        'input' => 'required|string',
        ], [
        'input.required' => 'Введите хотя бы один домен',
        'input.string' => 'Данные должны быть строкой',
        ]);

        $domains = collect(preg_split('/[\n,]+/', $this->input))
            ->map(fn($d) => trim($d))
            ->filter()
            ->unique();

        $this->results = [];

        foreach ($domains as $domain) {
            if (!$this->isValidDomain($domain)) {
                $this->results[$domain] = 'Невалидный домен';
                continue;
            }

            if (Str::endsWith($domain, '.test')) {
                $this->results[$domain] = 'Доступен для покупки';
            } else {
                $date = now()->addDays(rand(10, 365))->format('d.m.Y');
                $this->results[$domain] = "Занят (до {$date})";
            }
        }
    }

    public function isValidDomain($domain): bool
    {
        return (bool) filter_var('http://' . $domain, FILTER_VALIDATE_URL);
    }

    public function render()
    {
        return view('livewire.domain-checker');
    }
}
