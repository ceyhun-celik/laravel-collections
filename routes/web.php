<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/macro', function (): array {
    Collection::macro('addCeyhun', function (): Collection {
        return $this->map(function (string $value) {
            return "Ceyhun: {$value}";
        });
    });

    return collect(['foo', 'bar'])->addCeyhun()->all();

    /*
        [
            "Ceyhun: foo",
            "Ceyhun: bar"
        ]
    */
});

/**
 * @return array<int, int>
 */
Route::get('/all', function (): array {
    return collect([1, 2, 3, 4])->all();

    /*
        [
            1,
            2,
            3,
            4
        ]
    */
});

Route::prefix('average')->group(function (): void {
    Route::get('with-key', function (): int|float {
        return collect([
            ['foo' => 10],
            ['foo' => 10],
            ['foo' => 20],
            ['foo' => 40],
        ])
        ->avg('foo');

        // 20
    });

    Route::get('without-key', function (): int|float {
        return collect([1, 1, 2, 4])
            ->avg();

        // 2
    });
});

/**
 * @return array<int, mixed>
 */
Route::get('/chunk', function (): array {
    return collect([1, 2, 3, 4, 5, 6, 7])
        ->chunk(4)
        ->all();

    /*
        [
            [
                1,
                2,
                3,
                4
            ],
            {
                "4": 5,
                "5": 6,
                "6": 7
            }
        ]
    */
});

/**
 * @return array<int, int>
 */
Route::get('/collapse', function (): array {
    return collect([
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ])
    ->collapse()
    ->all();

    /*
        [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9
        ]
    */
});

/**
 * @return array <string, mixed>
 */
Route::get('/combine', function (): array {
    $collection = collect(['name', 'age']);

    return $collection
        ->combine(['George', 29])
        ->all();

    /*
    {
        "name": "George",
        "age": 29
    }
    */
});

/**
 * @return array<int, string>
 */
Route::get('/concat', function (): array {
    return collect(['John Doe'])
        ->concat(['Jane Doe'])
        ->concat(['name' => 'Jonny Doe'])
        ->all();

    /*
        [
            "John Doe",
            "Jane Doe",
            "Jonny Doe"
        ]
    */
});

Route::prefix('contains')->group(function (): void {
    Route::get('/without-key', function (): bool {
        return collect([1, 2, 3, 4, 5])
            ->contains(fn (int $value, int $key): bool => $value > 5);

        // false
    });

    Route::get('with-key/1', function (): bool {
        return collect(['name' => 'Desk', 'price' => 100])
            ->contains('Desk');

        // true
    });

    Route::get('with-key/2', function (): bool {
        return collect(['name' => 'Desk', 'price' => 100])
            ->contains('New York');

        // false
    });

    Route::get('with-key/3', function (): bool {
        return collect([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Ball', 'price' => 100],
        ])
        ->contains('product', 'Bookcase');

        // false
    });

    Route::get('with-key/4', function (): bool {
        return collect([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Ball', 'price' => 100],
        ])
        ->contains('product', 'Ball');

        // true
    });
});

Route::get('/contains-one-item/1', function (): bool {
    return collect([])
        ->containsOneItem();

    // false
});

Route::get('/contains-one-item/2', function (): bool {
    return collect(['1'])
        ->containsOneItem();

    // true
});

Route::get('/contains-one-item/3', function (): bool {
    return collect(['1', '2'])
        ->containsOneItem();

    // false
});

Route::get('/count', function (): int {
    return collect([11, 22, 33, 44])
        ->count();

    // 4
});

/**
 * @return array<int, int>
 */
Route::get('/count-by/1', function (): array {
    return collect([1, 2, 2, 2, 2, 3, 3])
        ->countBy()
        ->all();

    /*
        {
            "1": 1,
            "2": 4,
            "3": 2
        }
    */
});

/**
 * @return array<string, int>
 */
Route::get('/count-by/2', function (): array {
    return collect(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com'])
        ->countBy(fn (string $email): string => substr(strrchr($email, '@'), 1))
        ->all();

    /*
        {
            "gmail.com": 2,
            "yahoo.com": 1
        }
    */
});

/**
 * @return array<int, array>
 */
Route::get('/cross-join/1', function (): array {
    return collect([1, 2])
        ->crossJoin(['a', 'b'])
        ->all();
    
    /*
        [
            [
                1, "a"
            ],
            [
                1, "b"
            ],
            [
                2, "a"
            ],
            [
                2, "b"
            ]
        ]
    */
});

/**
 * @return array<int, array>
 */
Route::get('/cross-join/2', function (): array {
    return collect([1, 2])
        ->crossJoin(['a', 'b'], ['I', 'II'])
        ->all();
    
    /*
        [
            [
                1, "a", "I"
            ],
            [
                1, "a", "II"
            ],
            [
                1, "b", "I"
            ],
            [
                1, "b", "II"
            ],
            [
                2, "a", "I"
            ],
            [
                2, "a", "II"
            ],
            [
                2, "b", "I"
            ],
            [
                2, "b", "II"
            ]
        ]
    */
});

Route::get('/dd', function (): void {
    collect(['John Doe', 'Jane Doe'])
        ->dd();

    /*
        array:2 [▼ // routes/web.php:314
            0 => "John Doe"
            1 => "Jane Doe"
        ]
    */
});

/**
 * @return array<int, int>
 */
Route::get('/diff', function (): array {
    return collect([1, 2, 3, 4, 5,])
        ->diff([2, 4, 6, 8])
        ->all();

    /*
        {
            "0": 1,
            "2": 3,
            "4": 5
        }
    */
});

/**
 * @return array<string, mixed>
 */
Route::get('/diff-assoc', function (): array {
    return collect([
        'color' => 'orange',
        'type' => 'fruit',
        'remain' => 6,
    ])
    ->diffAssoc([
        'color' => 'yellow',
        'type' => 'fruit',
        'remain' => 3,
        'used' => 6,
    ])
    ->all();

    /*
        {
            "color": "orange",
            "remain": 6
        }
    */
});

/**
 * @return array<string, int>
 */
Route::get('/diff-keys', function (): array {
    return collect([
        'one' => 10,
        'two' => 20,
        'three' => 30,
        'four' => 40,
        'five' => 50,
    ])
    ->diffKeys([
        'two' => 2,
        'four' => 4,
        'six' => 6,
        'eight' => 8,
    ])
    ->all();

    /*
        {
            "one": 10,
            "three": 30,
            "five": 50
        }
    */
});

Route::get('doesnt-contain/1', function (): bool {
    return collect([1, 2, 3, 4, 5])
        ->doesntContain(fn (int $value, int $key): bool => $value < 5);

    // false
});

Route::get('doesnt-contain/2', function (): bool {
    return collect([5, 6, 7, 8, 9])
        ->doesntContain(fn (int $value, int $key): bool => $value < 5);

    // true
});

Route::get('doesnt-contain/3', function (): bool {
    return collect([
        ['product' => 'Desk', 'price' => 200],
        ['product' => 'Ball', 'price' => 100],
    ])
    ->doesntContain('product', 'Bookcase');

    // true
});

Route::get('doesnt-contain/4', function (): bool {
    return collect([
        ['product' => 'Desk', 'price' => 200],
        ['product' => 'Ball', 'price' => 100],
    ])
    ->doesntContain('product', 'Ball');

    // false
});

/**
 * @return array<string, int>
 */
Route::get('/dot', function (): array {
    return collect(['products' => ['desk' => ['price' => 100]]])
        ->dot()
        ->all();

    /*
        {
            "products.desk.price": 100
        }
    */
});

Route::get('/dump', function (): void {
    collect(['John Doe', 'Jane Doe'])
        ->dump();

    /*
        array:2 [▼ // vendor/laravel/framework/src/Illuminate/Routing/CallableDispatcher.php:40
            0 => "John Doe"
            1 => "Jane Doe"
        ]
    */
});

/**
 * @return array<int, string>
 */
Route::get('/duplicates/1', function (): array {
    return collect(['a', 'b', 'a', 'c', 'b'])
        ->duplicates()
        ->all();

    /*
        {
            "2": "a",
            "4": "b"
        }
    */
});

/**
 * @return array<int, string>
 */
Route::get('/duplicates/2', function (): array {
    return collect([
        ['email' => 'abigail@example.com', 'position' => 'Developer'],
        ['email' => 'james@example.com', 'position' => 'Designer'],
        ['email' => 'victoria@example.com', 'position' => 'Developer'],
    ])
    ->duplicates('position')
    ->all();

    /*
        {
            "2": "Developer"
        }
    */
});

Route::get('/each/1', function (): void {
    collect([1, 2, 3, 4])->each(function (int $item, int $key) {
        //
    });
});

Route::get('/each/2', function (): void {
    collect([1, 2, 3, 4])->each(function (int $item, int $key) {
        if ($key === 4) {
            return false;
        }
    });
});

Route::get('/each-spread', function (): void {
    collect([['John Doe', 35], ['Jane Doe', 33]])->eachSpread(function (string $name, int $age) {
        //
    });
});

Route::get('/every/1', function (): bool {
    return collect([1, 2, 3, 4])
        ->every(fn (int $value, int $key): bool => $value > 2);

    // false
});

Route::get('/every/2', function (): bool {
    return collect([3, 4])
        ->every(fn (int $value, int $key): bool => $value > 2);

    // true
});

Route::get('/every/3', function (): bool {
    return collect([])
        ->every(fn (int $value, int $key): bool => $value > 2);

    // true
});

/**
 * @return array<string, mixed>
 */
Route::get('/except', function (): array {
    return collect(['product_id' => 1, 'price' => 100, 'discount' => false])
        ->except(['price', 'discount'])
        ->all();

    /*
        {
            "product_id": 1
        }
    */
});

/**
 * @return array<int, int>
 */
Route::get('/filter/1', function (): array {
    return collect([1, 2, 3, 4])
        ->filter(fn (int $value, int $key) => $value > 2)
        ->all();

    /*
        {
            "2": 3,
            "3": 4
        }
    */
});

/**
 * @var array<int, int>
 */
Route::get('/filter/2', function (): array {
    return collect([1, 2, 3, null, false, '', 0, []])
        ->filter()
        ->all();

    /*
        [
            1,
            2,
            3
        ]
    */
});

Route::get('/first/1', function (): int {
    return collect([1, 2, 3, 4])
        ->first(fn (int $value, int $key): int => $value > 2);

    // 3
});

Route::get('/first/2', function (): int {
    return collect([5, 6, 7, 8])
        ->first();

    // 5
});

Route::get('/first-or-fail', function (): int {
    return collect([1, 2, 3, 4])
        ->firstOrFail(fn (int $value, int $key): int => $value > 5);

    // Throws ItemNotFoundException...
});

/**
 * @return array<string, mixed>
 */
Route::get('/first-where/1', function (): array {
    return collect([
        ['name' => 'Regena', 'age' => null],
        ['name' => 'Linda', 'age' => 14],
        ['name' => 'Diego', 'age' => 23],
        ['name' => 'Linda', 'age' => 84],
    ])
    ->firstWhere('name', 'Linda');

    /*
        {
            "name": "Linda",
            "age": 14
        }
    */
});

/**
 * @return array<string, mixed>
 */
Route::get('/first-where/2', function (): array {
    return collect([
        ['name' => 'Regena', 'age' => null],
        ['name' => 'Linda', 'age' => 14],
        ['name' => 'Diego', 'age' => 23],
        ['name' => 'Linda', 'age' => 84],
    ])
    ->firstWhere('age', '>=', 18);

    /*
        {
            "name": "Diego",
            "age": 23
        }
    */
});

/**
 * @return array<string, mixed>
 */
Route::get('/first-where/3', function (): array {
    return collect([
        ['name' => 'Regena', 'age' => null],
        ['name' => 'Linda', 'age' => 14],
        ['name' => 'Diego', 'age' => 23],
        ['name' => 'Linda', 'age' => 84],
    ])
    ->firstWhere('age');

    /*
        {
            "name": "Linda",
            "age": 14
        }
    */
});

/**
 * @return array<string, mixed>
 */
Route::get('/flat-map', function (): array {
    return collect([
        ['name' => 'Sally'],
        ['school' => 'Arkansas'],
        ['age' => 28],
    ])
    ->flatMap(fn (array $values) => array_map('strtoupper', $values))
    ->all();

    /*
        {
            "name": "SALLY",
            "school": "ARKANSAS",
            "age": "28"
        }
    */
});

/**
 * @return array<int, string>
 */
Route::get('/flatten/1', function (): array {
    return collect([
        'name' => 'taylor',
        'languages' => [
            'php', 'javascript',
        ],
    ])
    ->flatten()
    ->all();

    /*
        [
            "taylor",
            "php",
            "javascript"
        ]
    */
});

/**
 * @return array<int, string>
 */
Route::get('/flatten/2', function (): array {
    return collect([
        'Apple' => [
            [
                'name' => 'iPhone XR',
                'brand' => 'Apple',
            ],
        ],
        'Samsung' => [
            [
                'name' => 'Galaxy S7',
                'brand' => 'Samsung',
            ],
        ],
    ])
    ->flatten()
    ->all();

    /*
        [
            "iPhone XR",
            "Apple",
            "Galaxy S7",
            "Samsung"
        ]
    */
});

/**
 * @return array<int, array>
 */
Route::get('/flatten/3', function (): array {
    return collect([
        'Apple' => [
            [
                'name' => 'iPhone XR',
                'brand' => 'Apple',
            ],
        ],
        'Samsung' => [
            [
                'name' => 'Galaxy S7',
                'brand' => 'Samsung',
            ],
        ],
    ])
    ->flatten(1)
    ->all();

    /*
        [
            {
                "name": "iPhone XR",
                "brand": "Apple"
            },
            {
                "name": "Galaxy S7",
                "brand": "Samsung"
            }
        ]
    */
});

/**
 * @return array<string, string>
 */
Route::get('/flip', function (): array {
    return collect(['name' => 'taylor', 'framework' => 'laravel'])
        ->flip()
        ->all();

    /*
        {
            "taylor": "name",
            "laravel": "framework"
        }
    */
});

/**
 * @var array<string, string>
 */
Route::get('/forget', function (): array {
    return collect(['name' => 'taylor', 'framework' => 'laravel'])
        ->forget('name')
        ->all();

    /*
        {
            "framework": "laravel"
        }
    */
});

/**
 * @return array<int, int>
 */
Route::get('for-page', function (): array {
    return collect([1, 2, 3, 4, 5, 6, 7, 8, 9])
        ->forPage(2, 3)
        ->all();

    /*
        {
            "3": 4,
            "4": 5,
            "5": 6
        }
    */
});

Route::get('/get/1', function (): string {
    return collect(['name' => 'taylor', 'framework' => 'laravel'])
        ->get('framework');

    // laravel
});

Route::get('/get/2', function (): int {
    return collect(['name' => 'taylor', 'framework' => 'laravel'])
        ->get('age', 34);

    // 34
});

Route::get('/get/3', function (): string {
    return collect(['name' => 'taylor', 'framework' => 'laravel'])
        ->get('email', fn (): string => 'taylor@example.com');

    // taylor@example.com
});

/**
 * @return array<string, array>
 */
Route::get('group-by/1', function (): array {
    return collect([
        ['account_id' => 'account-x10', 'product' => 'Chair'],
        ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ['account_id' => 'account-x11', 'product' => 'Desk'],
    ])
    ->groupBy('account_id')
    ->all();

    /*
        {
            "account-x10": [
                {
                    "account_id": "account-x10",
                    "product": "Chair"
                },
                {
                    "account_id": "account-x10",
                    "product": "Bookcase"
                }
            ],
            "account-x11": [
                {
                    "account_id": "account-x11",
                    "product": "Desk"
                }
            ]
        }
    */
});

/**
 * @return array<string, array>
 */
Route::get('group-by/2', function (): array {
    return collect([
        ['account_id' => 'account-x10', 'product' => 'Chair'],
        ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ['account_id' => 'account-x11', 'product' => 'Desk'],
    ])
        ->groupBy(fn (array $item, int $key): string => substr($item['account_id'], -3))
        ->all();

    /*
        {
            "x10": [
                {
                    "account_id": "account-x10",
                    "product": "Chair"
                },
                {
                    "account_id": "account-x10",
                    "product": "Bookcase"
                }
            ],
            "x11": [
                {
                    "account_id": "account-x11",
                    "product": "Desk"
                }
            ]
        }
    */
});

Route::get('/has/1', function (): bool {
    return collect(['account_id' => 1, 'product' => 'Desk', 'amount' => 5])
        ->has('product');

    // true
});

Route::get('/has/2', function (): bool {
    return collect(['account_id' => 1, 'product' => 'Desk', 'amount' => 5])
        ->has('product', 'amount');

    // true
});

Route::get('/has/3', function (): bool {
    return collect(['account_id' => 1, 'product' => 'Desk', 'amount' => 5])
        ->has('amount', 'price');

    // false
});

Route::get('/has-any/1', function (): bool {
    return collect(['account_id' => 1, 'product' => 'Desk', 'amount' => 5])
        ->hasAny(['product', 'price']);

    // true
});

Route::get('/has-any/2', function (): bool {
    return collect(['account_id' => 1, 'product' => 'Desk', 'amount' => 5])
        ->hasAny(['name', 'price']);

    // false
});

Route::get('/implode/1', function (): string {
    return collect([
        ['account_id' => 1, 'product' => 'Desk'],
        ['account_id' => 2, 'product' => 'Ball'],
    ])
    ->implode('product', ', ');

    // Desk, Ball
});

Route::get('/implode/2', function (): string {
    return collect([1, 2, 3, 4, 5])->implode('-');

    // 1-2-3-4-5
});

Route::get('/implode/3', function (): string {
    return collect([
        ['account_id' => 1, 'product' => 'Desk'],
        ['account_id' => 2, 'product' => 'Ball'],
    ])
    ->implode(fn (array $item, int $key) => strtoupper($item['product']), ', ');

    // DESK, BALL
});

/**
 * @return array<int, string>
 */
Route::get('/intersect', function (): array {
    return collect(['Desk', 'Sofa', 'Chair'])
        ->intersect(['Desk', 'Chair', 'Bookcase'])
        ->all();

    /*
        {
            "0": "Desk",
            "2": "Chair"
        }
    */
});

/**
 * @return array<string, string>
 */
Route::get('intersect-assoc', function (): array {
    return collect([
        'color' => 'red',
        'size' => 'M',
        'material' => 'cotton',
    ])
    ->intersectAssoc([
        'color' => 'blue',
        'size' => 'M',
        'material' => 'polyester'
    ])
    ->all();

    /*
        {
            "size": "M"
        }
    */
});

/**
 * @return array<string, mixed>
 */
Route::get('intersect-by-keys', function (): array {
    return collect([
        'serial' => 'UX301',
        'type' => 'screen',
        'year' => 2009,
    ])
    ->intersectByKeys([
        'reference' => 'UX404',
        'type' => 'tab',
        'year' => 2011,
    ])
    ->all();

    /*
        {
            "type": "screen",
            "year": 2009
        }
    */
});

Route::get('/is-empty', function (): bool {
    return collect([])->isEmpty();

    // true
});

Route::get('/is-not-empty', function (): bool {
    return collect([])->isNotEmpty();

    // false
});

Route::get('/join/1', function (): string {
    return collect(['a', 'b', 'c'])->join(', ');

    // a, b, c
});

Route::get('/join/2', function (): string {
    return collect(['a', 'b', 'c'])->join(', ', ', and ');

    // a, b, and c
});

Route::get('/join/3', function (): string {
    return collect(['a', 'b'])->join(', ', ' and ');

    // a and b
});

Route::get('/join/4', function (): string {
    return collect(['a'])->join(', ', ' and ');

    // a
});

Route::get('/join/5', function (): string {
    return collect([])->join(', ', ' and ');

    //
});

/**
 * @return array<string, array>
 */
Route::get('key-by/1', function (): array {
    return collect([
        ['product_id' => 'prod-100', 'name' => 'Desk'],
        ['product_id' => 'prod-200', 'name' => 'Ball'],
    ])
    ->keyBy('product_id')
    ->all();

    /*
        {
            "prod-100": {
                "product_id": "prod-100",
                "name": "Desk"
            },
            "prod-200": {
                "product_id": "prod-200",
                "name": "Ball"
            }
        }
    */
});

/**
 * @return array<string, array>
 */
Route::get('key-by/2', function (): array {
    return collect([
        ['product_id' => 'prod-100', 'name' => 'Desk'],
        ['product_id' => 'prod-200', 'name' => 'Ball'],
    ])
    ->keyBy(fn (array $item, int $key) => strtoupper($item['product_id']))
    ->all();

    /*
        {
            "PROD-100": {
                "product_id": "prod-100",
                "name": "Desk"
            },
            "PROD-200": {
                "product_id": "prod-200",
                "name": "Ball"
            }
        }
    */
});

/**
 * @return array<int, string>
 */
Route::get('/keys', function (): array {
    return collect([
        'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
        'prod-200' => ['product_id' => 'prod-200', 'name' => 'Ball'],
    ])
    ->keys()
    ->all();

    /*
        [
            "prod-100",
            "prod-200"
        ]
    */
});

Route::get('/last/1', function (): int {
    return collect([1, 2, 3, 4])->last(fn (int $value, int $key) => $value < 3);

    // 2
});

Route::get('/last/2', function (): int {
    return collect([1, 2, 3, 4])->last();

    // 4
});

Route::get('/lazy', function (): string {
    return get_class(collect([1, 2, 3, 4])->lazy());

    // Illuminate\Support\LazyCollection
});