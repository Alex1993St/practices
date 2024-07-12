<?php

namespace App\Http\Controllers;

use App\Services\ElasticSearchService;
use Illuminate\Http\Request;
use Elastic\Elasticsearch\Helper\Iterators\SearchResponseIterator;

class ElasticController extends Controller
{
    private $index = 'practice';

    public function __construct(
        public ElasticSearchService $elasticSearchService
    )
    {
    }

    public function index()
    {
        $this->elasticSearchService->index($this->index);
        $items = $this->elasticSearchService->getAll($this->index);

        return view('elastic.index', [
            'items' => $items
        ]);
    }

    public function store(Request $request)
    {
        $this->elasticSearchService->index($this->index);
        $this->elasticSearchService->addDocument($this->index, $request->except('_token'));

        return redirect(route('elastic.index'));
    }

    public function delete(Request $request)
    {
        $this->elasticSearchService->removeDocument($this->index, $request->get('id'));

        return redirect(route('elastic.index'));
    }
}
