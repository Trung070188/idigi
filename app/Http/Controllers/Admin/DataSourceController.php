<?php

namespace App\Http\Controllers\Admin;

use App\Models\FileProductCategory;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DataSourceController extends AdminBaseController
{
    public function index(Request $request)
    {
        $datasource = $request->get('datasource');
        $method = 'ds' . ucfirst($datasource);
        $keyword = $request->get('query');
        $values = $request->get('values');
        $values = $values ? explode(',', $values): null;
        $keyword = "%".$keyword.'%';

        return $this->$method($request, $keyword, $values);
    }

    public function getMany(Request $request)
    {
        $entries = $request->get('entries');
        $result = [];
        foreach ($entries as $entry) {
            try {
                $requestId = $entry['request_id'];
                $datasource = $entry['datasource'];
                $params = $entry['params'];

                $keyword = $params['query'] ?? '';
                $values = $params['values'] ?? null;
                $values = $values ? explode(',', $values): null;
                $keyword = "%".$keyword.'%';

                $method = 'ds' . ucfirst($datasource);
                $result[$requestId] = $this->$method($request, $keyword, $values);
            } catch (\Exception $e) {
                $result[] = [
                    'code' => 503,
                    'data' => [],
                    'message' => $e->getMessage()
                ];
            }
        }


        return [
            'code' => 200,
            'data' => [],
            'result' => $result
        ];
    }

    public function dsTest01()
    {
        $content = file_get_contents(database_path('/sample/category.txt'));

        return [
            'code' => 200,
            'data' => json_decode($content)
        ];
    }

    public function dsCategories()
    {
//        $categories = FileProductCategory::getProductCategories();
//
//        return [
//            'code' => 200,
//            'data' => $categories['tree']
//        ];
    }


    private function dsUsers(Request $request, $keyword, $values)
    {
        $query = User::query()
            ->selectRaw('id,`email` as label')
            ->limit(15);

        $selectedUsers = [];
        if (!empty($values)) {
            $selectedUsers = $query->clone()->whereIn('id', $values)->get()->toArray();
            $query->whereNotIn('id', $values);
        }

        $query->where('username', 'LIKE', $keyword)
            ->orWhere('email', 'LIKE', $keyword);

        $users = $query->limit(15)->get()->toArray();

        return [
            'code' => 200,
            'data' => array_merge($selectedUsers, $users)
        ];
    }

    private function dsTerms(Request $request)
    {
        return [
            'code' => 200,
            'data' => Term::getTree()
        ];
    }

    private function dsTags(Request $request, $keyword, $values)
    {
        $query = Tag::query()
            ->limit(25)
            ->selectRaw('id,`name`');

        $selectedTags = [];
        if (!empty($values)) {
            $selectedTags = $query->clone()->whereIn('id', $values)->get()->toArray();
            $query->whereNotIn('id', $values);
        }

        $tags = $query->where('name', 'LIKE', $keyword)->get()->toArray();

        return [
            'code' => 200,
            'data' => array_merge($selectedTags, $tags)
        ];
    }

    private function dsProducts(Request $request, $keyword, $values)
    {
      /*  return [
            'code' => 200,
            'data' => []
        ];*/
        //$keyword = $request->get('query');

        ///$values = $request->get('values');

//        $url = Post::URL_GET_PRODUCT;
//
//        if (!empty($values)) {
//            $keyword = implode(',', $values);
//            $url = Post::URL_SEARCH_SKU;
//        }
//
//        $url = sprintf($url, "%{$keyword}%");
//        $baseUrl = config('services.happynest_products.baseUrl');
//        $token = config('services.happynest_products.token');
//        $client = new Client([
//            'headers' => [
//                'Authorization' => 'Bearer ' . $token
//            ]
//        ]);
//
//        try {
//
//            $resp = $client->get($baseUrl . $url);
//        } catch (\Exception $e) {
//            return [
//                'code' => 200,
//                'message' => $e->getMessage(),
//                'data' => []
//            ];
//        }
//
//
//        $data = [];
//        $json = null;
//        if($resp->getStatusCode() == 200) {
//            $json = json_decode($resp->getBody(), true);
//
//            if($json && is_array($json) && isset($json['items'])) {
//                foreach ($json['items'] as $item) {
//                    $product = [
//                        'id' => $item['sku'],
//                        'name' => $item['name'],
//                        'product_price' => $item['price'],
//                        'product_url' => @$item['extension_attributes']['link_redirect_sku'],
//                        'product_vendor' => ''
//                    ];
//                    if(isset($item['media_gallery_entries']) && is_array($item['media_gallery_entries'])) {
//                        foreach ($item['media_gallery_entries'] as $media) {
//                            if($media['media_type'] == 'image') {
//                                $product['product_image'] = "$baseUrl/media/catalog/product" . $media['file'];
//                            }
//                        }
//                    }
//                    $data[] = $product;
//                }
//            }
//        }

//        return [
//            'code' => 200,
//            'data' => $data
//        ];

        return [
            'code' => 200,
            'data' => []
        ];
    }

    private function dsAlbumRelateds(Request $request, $keyword, $values)
    {
        $query = Post::query()
            ->where('post_type_id', Post::ALBUM)
            ->limit(25)
            ->select('id', 'title as name');

        $selectedAlbums = [];
        if (!empty($values)) {
            $selectedAlbums = $query->clone()->whereIn('id', $values)->get()->toArray();
            $query->whereNotIn('id', $values);
        }

        $albums = $query->where('title', 'LIKE', $keyword)->get()->toArray();

        return [
            'code' => 200,
            'data' => array_merge($selectedAlbums, $albums)
        ];
    }

    private function dsPostTitles(Request $request,$keyword, $values)
    {

        $query = Post::query()
            ->whereNotNull('title')
            ->limit(25)
            ->select('id', 'title as name');

        $selectedPosts = [];
        if (!empty($values)) {
            $selectedPosts = $query->clone()->whereIn('id', $values)->get()->toArray();
            $query->whereNotIn('id', $values);
        }

        $query->where('title', 'LIKE', $keyword);

        return [
            'code' => 200,
            'data' => array_merge($selectedPosts, $query->get()->toArray())
        ];
    }

    private function dsTopics(Request $request,$keyword, $values) {
        $query = Term::query()
            ->where('status_code', 1 )
            ->where('type', 'Category')
            ->limit(25)
            ->select('id', 'name');

        $selected = [];
        if (!empty($values)) {
            $selected = $query->clone()->whereIn('id', $values)->get()->toArray();
            $query->whereNotIn('id', $values);
        }

        $query->where('name', 'LIKE', $keyword);

        return [
            'code' => 200,
            'data' => array_merge($selected, $query->get()->toArray())
        ];
    }

    private function dsPlaces(Request $request,$keyword, $values) {
        return [
            'code' => 200,
            'data' => Term::getPlaces()
        ];
    }

    private function dsStyles(Request $request, $keyword, $values) {

        return [
            'code' => 200,
            'data' => Term::getStyles()
        ];
    }

    private function dsHomeTypes(Request $request, $keyword, $values) {
        return [
            'code' => 200,
            'data' => Term::getHomeTypes()
        ];
    }

    private function dsPostsByTerm(Request $request)
    {
        $keyword = $request->get('query');
        $values = $request->get('values');
        $values = $values ? explode(',', $values): null;
        $term = $request->get('term_id');

        $keyword = "%".$keyword.'%';
        $query = Post::query()
            ->join('post_term', "post.id", '=', 'post_term.post_id')
            ->where('post_term.term_id', $term)
            ->limit(15)
            ->select('id', 'title as name', 'description');

        $selected = [];

        if (!empty($values)) {
            $selected = $query->clone()->whereIn('id', $values)->get()->toArray();
            $query->whereNotIn('id', $values);
        }

        $query->where('title', 'LIKE', $keyword);

        return [
            'code' => 200,
            'data' => array_merge($selected, $query->get()->toArray())
        ];
    }

}
