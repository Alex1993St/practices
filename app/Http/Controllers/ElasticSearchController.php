<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class ElasticSearchController extends Controller
{
    public $client;

    /**
     * Базове підключення
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('elastic.endpoint')])
            ->setApiKey(config('elastic.api_key'))
            ->build();
    }

    /**
     * Ствоерння індексу
     */
    public function createIndex()
    {
        $params = [
            'index' => 'my_index'
        ];

        return $this->client->indices()->create($params)->asArray();
    }

    /**
     * Додавання документу
     */
    public function addDocument()
    {
        $params = [
            'index' => 'my_index',
            'body' => [
                'testField' => 'asd'
            ]
        ];

        return $this->client->index($params)->asArray(); // свторює індекс

    }

    /**
     *  Мас додавання документу
     */
    public function massAddDocument()
    {
        $params = ['body' => []];

        // якщо _id вже буде існувати то данні в ньому оновить
        for ($i = 1; $i <= 2; $i++) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'my_index',
                    '_id' => $i
                ]
            ];

            $params['body'][] = [
                'my_field' => 'my_value',
                'second_field' => 'some more values'
            ];


            $responses = $this->client->bulk($params);
        }

        return $responses->asArray();
    }

    /*
     * Інформація по документу
     */
    public function getDocument()
    {
        $params = [
            'index' => 'my_index',
            'id' => '1'
        ];

        return $this->client->get($params)->asArray(); //  достає індекс по ід
    }

    /*
     *  Глобальна інформація
     */
    public function getInfo()
    {
        $params = [
            'index' => 'my_index',
            'id' => 2 // не обов'язковий параметр
        ];

        return $this->client->info($params)->asArray();
    }

    /**
     *  отримати список доступних запитів
     */
    public function getCatHelp()
    {
        return $this->client->cat()->help()->asString();
    }

    /*
     *   отримати  доступні індекси по індексу перші 10
     */
    public function getCatIndices()
    {
        return $this->client->cat()->indices()->asString();
    }

    /*
     * виведе перший доступний індекс
     * в hits лежать всі документи
     *    {
            "_index":"my_index",
            "_id":"Z9FiKo8BWwL6jXAp-T0W",
            "_score":1.0,"_
            source":{ // данні які лежать в документі
            "testField":"abc"}
        }
     */
    public function getFirstIndex()
    {
        return $this->client->search([
            'index' => 'my_index'
        ])->asArray();
    }

    /*
     * \ Видалити індекс
     */
    public function removeIndex()
    {
        return $this->client->indices()->delete(['index' => 'my_index'])->asArray();
    }

    /*
     *  Видалити документ
     */
    public function removeDocument()
    {
        return $this->client->delete(['index' => 'my_index', 'id' => '2'])->asArray();
    }

    /*
     * Отримати інформацію по шардам
     */
    public function getShard()
    {
        return $this->client->cat()->shards()->asString();
    }

    /*
     * оновити плое в доку // ЕЛАСТІК ПОВНІСТЮ ПЕРЕЗАПУСУЄ ДОКУМЕНТ І ОНОВЛЮЄ число в version х
     *  хз в мене додлало
     */
    public function update()
    {
        return $this->client->update([
            'index' => 'my_index',
            'id' => 'lbacv48BWmW-TfIpSygA',
            'body' => [
                'doc' => [
                    'test_Field' => 'TEST'
                ]
            ]
        ])->asArray();
    }

    /*
     *  дізнатись на якому шарді док
     */
    public function findShardByDoc()
    {
        return $this->client->searchShards([
            'index' => 'my_index',
            // 'routing' => 'ctG9To8BWwL6jXApM0IP'
        ])->asArray();
    }

    /*
     * пошук даних в  документі
     */
    public function getSearch()
    {
        return $this->client->search([
            'index' => 'my_index',
            'body'  => [
                'query' => [
                    'term' => [
                        'testField' => [
                            'value' => "test",
                        ]
                    ],

//                    'range' => [
//                        'testField' => [
//                            'gte' => '',
//                            'lte' => ''
//                        ]
//                    ]
                ]
            ]
        ])->asArray();
    }



    public function index()
    {
        //$this->description();

       //$response = $this->createIndex();
       //$response = $this->addDocument();
       //$response = $this->massAddDocument();
        //$response = $this->getDocument();
        //$response = $this->getInfo();
        //$response = $this->getCatHelp()->asString();
       // $response = $this->getCatIndices();
        //$response = $this->getFirstIndex(); // отримає список документів
       // $response = $this->removeIndex();
       // $response = $this->removeDocument();
        //$response = $this->getShard();
        //$response = $this->update();
        //$response = $this->findShardByDoc();
        $response = $this->getSearch();
        dd($response);



      //  dd($response, $response->asArray());

    }

    private function description()
    {
//        // node // одиничний сервер де лежать індекси
//        // індексірованіе ппроцес вставки документа в індекс
//        // node - управляє запитами, групіровка, сортування, count. аналитические запроси і т.д.
//
//
//        // cluster // об'єжнання node
//
//        // shard // куски індексор которие распихиваем по нодам
//        // якщо занадто великий index  воно його розбиває на куски і засуне в різні node
//        // кожний такий кусок це шард
//        // node має як оригінал primery так і копію replicant тільки копія зберігається в інших нодах
//        // щоб якщо якась нода впала данні не тірялись

        //        Аналізатор
//        /_analyze
//        приймає
//        text - будь яка строка
//        char_filter - вик для заміни обо видалення символіз з троки text
//            char_filter {
//                type: 'mapping',
//                mapping: [
//                    умови
//                    "c => ''", це означає замі буку с на пусте значенн
//                    "c => Ж", це означає замі буку с на Ж
//
//         }
//
//         tokenizer - як буде розьбиватись строка (text)
//         tokenizer: "standard" - базовий розбиває по пробелам, комам, крапкам і т.д.
//         tokenizer: "whitespace" - розбиває лише по пробілу // ТУТ НЕ ТОЧНО ВПЕВНЕНИЙ ЩО Є
//         tokenizer: "keyword" - бере все речення (скоріше всього що до .)
//
//         filter - як обробити tokenizer (слова які вийшли)
//         filter: ["uppecase"] чи ["lowercase"] приведе до верхного / нижного регістру слова
//
//        /_analyze можна задати і без доп параметрів зробити так
//        text - будь яка строка
//        analyzer: whitespace  - розбиває лише по пробілу
//        analyzer: standard  - базовий розбиває по пробелам, комам, крапкам і т.д.

//        inverted index - це коли в нас є декілька документів кожний зі своїм знаяення
//        ці значення розбиваються на токени (слова) і створюється так би мовити таблиця
//        яка відсортована по алфавіту (а може і ні) і слова йдуть вряд
//        при пошукі слова "Hello" в цій таблиці шукається індекс (слово) Hello
//        і потім дивитиься в якому документі є це слово
//
//        Doc 1 = Hello world
//        Doc 2 = world My
//        Doc 3 = Hello world My
//
//        index | Doc 1| Doc 2 | Doc 3 |
//        World |  \/  |  \/  |   /    |
//        Hello |  \/  |      |   /    |
//        My    |      |  \/  |   /    |

//         результат буде Doc 1 i Doc 3
//         на кожний індекст (текстовий) створюється inverted index і теж займає пам'ять' (якщо не явний mapping)
//            для інших вик "красне чорне дерове" mapping
//            явний mapping - коли самі додаємо індекс
//            жинамічний mapping - коли еластік сам оприділяє тип данних і надає індекс
//        якщо вручну задавати mapping то це гарно тим що сам робиш яким буде тип кодних данних
//        і те що можна вказати що не всі поля будуть мати inverted index так як він потрібний лише для пошуку
//        якщо пошук по полям не буде відбуватись то inverted index і не треба
//        "mapping": {
//           "properties": {
//               "city": {
//                   "type": "text",
//                    "fields": {
//                       "keyword": { // додати підполе з назвою keyword"
//                           "type": "keyword", // тип підполя
//                           "ignore_above": 256 // не додавати строки більше 256
//                    }
//                }
//            },
//              "name": {
//                "type": "text",
//                    "fields": {
//                    "keyword": {
//                        "type": "keyword",
//                           "ignore_above": 256
//                    }
//                }
//            },
//            'posotion': {
//                   "type": "integer"
//            }
//
//        }
//    }

//       !!!! ГЛЯНУТЬ В ГУГЛІ ЯК РОБИТЬ МАПІНГ В ЕЛАСТІКУ!!!!


//        Elastic при відповіді в полі _source показує данні ісходні тобто ті
//        данні які були передані в документі, а не ті які записані в самом еастіку
//        (тобто ми могли вставити "123", в елмастіку в нас тип integer він конвертує в 123
//        і в нього в базі буде це число аое при запиті поверне нам "123" як спроку), щоб такого не було
//        при мапінгу додаємо параметр coerce: false
//        "mapping": {
//           "properties": {
//               "range": {
//                   "type": "integer",
//                    "coerce": false




//        // Точний пошук через match вик для пошуку текстових полів
//        $response = $client->search([
//            'index' => 'my_index',
//                'body'  => [
//                'query' => [
//                    'match' => [
//                        'testField' =>  'abc' // шукаю в документах поле rating де значення 123
//                    ]
//                ],
//            ],
//        ]);

        // Точний пошук через term не призначиний для пошуку по полям типу text
//        $response = $client->search([
//            'index' => 'my_index',
//            'body'  => [
//                'query' => [
//                    'term' => [
//                        'testField' =>  'abc' // шукаю в документах поле rating де значення 123
//                    ]
//                ],
//            ],
//        ]);

//        різниця між keyword i text в тому що в текст це inverted index а keyword це точний текст
//     тобто щоб знайти в документі текст  Hello word в
//        - keyword потрібно повністю прописати Hello word (term пошук)
//        - text достатньо або Hello або word (match пошук)


        // пошук по подполю keyword яке знаходиться в полі testField
//        $response = $client->search([
//            'index' => 'my_index',
//                'body'  => [
//                'query' => [
//                    'match' => [
//                        'testField.keyword' =>  'abc' // шукаю в документах поле rating де значення 123
//                    ]
//                ],
//            ],
//        ]);

        // додати масив але підкапотом записує як не масив так як такого поля не має
        // а він об'єднує строки по "пробіл" якщо буде масив  ["HEllo worlf", 'other text"]
        // то це буде 4 текста HEllo | worlf | other| text
        // запис [1,2,3]  або [1,"2",3]  але в ньому type буде лог, а дані виглядатимуть як масив
        // АЛЕ  якщо індекс ще не створенно і ми додаємо це [1,"2",3] то видасть помилку якщо немає явного мапінгу
        // бо данні неоднорідні треба або всі числа або всі строки!!!!
//        $response = $client->index([
//            'index' => 'my_index',
//            'body'  => [
//                'testField' => ['abc', 'abc'],
//                 'user_info' => [
//                       ['name' => 'Nika', 'age' => 25],
//                       ['name' => 'Misha', 'age' => 31]
//                 ]
//            ]
//        ]);

//        // поверне список данних
//        $response = $client->search([
//            'index' => 'my_index',
//        ]);

//        "mapping": {
//            properties": {
//                   "city": {
//                       "type": "text",
//                    }.
//                    "job": {
//                         properties": {
//                            "it": {
//                                "type": "text",
//                            },
//                              "salary": {
//                                "type": "number",
//                            },
//                        }
//                    }
//                },
//          }
//            приклад body
//           'body'  => [
//                'city' => 'Kiev',
//                 'job' => [
//                       ['it' => 'IT'],
//                       ['salary' => 1000]
//                 ]
//            ]
//          АНАЛОЧНА ЗАПИС

//             "mapping": {
//                properties": {
//                   "city": {
//                       "type": "text",
//                    }.
//                    "job.it": {
//                          "type": "text",
//                      },
//                      "job.salary": {
//                          "type": "number",
//                      },
//                 },
//              }
//              приклад body
//            'body'  => [
//                 'city' => 'Kiev',
//                 'job.it' => "IT",
//                 'job.salary' => 1000,
//            ]

//        Додати до вже готового мапінгу ТІЛЬКИ POST запит
//        "mapping": {
//                properties": {
//                   "data": {
//                       "type": "date",
//                    }.
//                 },
//              }


//        В еластіку є три типи для ДАТИ
//        дата без времені HH:ii:ss
//        дата с временем
//        мілісек з 1 января 1970 года
//        "mapping": {
//                properties": {
//                   "data": {
//                       "type": "date",
//                       "format": "dd/mm/YYYY", // не обов'язковий параметр
//                    }.
//                 },
//              }
//            'body'  => [
//                 'date' =>  "04/04/1993" не прокатить так як через / еластік не розуміє zroj ytvf' format
//                  date' =>  "1993-04-04" так ок
//                  date' =>  "1993-04-04T13:06:41Z" так ок
//                  date' =>  1234567890 так ок
//            ]

        // ДІАПАЗАОН
//        $response = $client->search([
//            'index' => 'my_index',
//            'query' => [
//                'range' => [
//                    'date' => [ // полк date
//                        'gte' => "2022-01-01", // great or equel діапазон  "больше чи равно"
//                        //'lte' =>  "2022-01-01" // діапазон  "менше чи равно"
//                    ]
//                ]
//            ]
//        ]);

        // ТОЧЯНЕ СПІВПАДІННЯ
//        $response = $client->search([
//            'index' => 'my_index',
//            'query' => [
//                'term' => [
//                    'date' => [ // полк date
//                        'value' => "2022-01-01",
//                    ]
//                ]
//            ]
//        ]);


//        $response = $client->search([
//            'index' => 'my_index',
//            'query' => [
//                'match_all' => []
//            ]
//        ]);
    }

}
