<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class DomainCheckController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
        'domains' => ['required', 'string'],
        ], [
        'domains.required' => 'Поле с доменами обязательно для заполнения.',
        'domains.string' => 'Данные должны быть строкой.',
        ]);

        $domains = collect(preg_split('/[\n,]+/', $request->input('domains')))
            ->map(fn($d) => trim($d))
            ->filter()
            ->unique();

        $results = [];

        foreach ($domains as $domain) {
            if (!filter_var('http://' . $domain, FILTER_VALIDATE_URL)) {
                $results[$domain] = 'invalid';
            } elseif (Str::endsWith($domain, '.test')) {
                $results[$domain] = 'available';
            } else {
                $results[$domain] = now()->addDays(rand(1, 365))->format('Y-m-d');
            }
        }

        return response()->json([
            'data' => $results
        ]);
    }
}
