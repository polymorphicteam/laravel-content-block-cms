<?php

namespace Cswiley\Cms;

use function array_intersect;
use function array_keys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function json_encode;

class CmsController extends Controller
{
    private $cms;

    public function __construct(Cms $cms)
    {
        $this->cms = $cms;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu = $this->cms->all();

        return view('cms::home', compact('menu'));
    }

    protected function validCmsStructure($newData, $prevData)
    {
        if (empty($newData) || empty($prevData)) {
            return false;
        }

        $a = array_keys($newData);
        $b = array_keys($prevData);

        return (count($a) === count($b)) && empty(array_diff($a, $b));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $data  = $request->input('data');

        // Validate title
        if (empty($title)) {
            return response()->json([
                'message' => "title is empty",
                'ok'      => false
            ]);
        }

        // Validate data
        if (empty($data)) {
            return response()->json([
                'message' => "data is empty",
                'ok'      => false
            ]);
        }

        // Validate file
        $prevData    = $this->cms->get($title);
        if (empty($prevData)) {
            return response()->json([
                'message' => "file ($title) not found",
                'ok'      => false
            ]);
        }

        $prevDataDot = array_dot($prevData);
        $newDataDot  = array_dot($data);

        // Validate saved data structure
        if (!$this->validCmsStructure($newDataDot, $prevDataDot)) {
            return response()->json([
                'message' => 'data structure invalid',
                'ok'      => false
            ]);
        }

        $json = json_encode($data);

        // validate json
        if (!$json) {
            return response()->json([
                'message' => 'json invalid',
                'ok'      => false
            ]);
        }

        $status = $this->cms->set($title, $json);

        return response()->json([
            'message' => 'json updated',
            'ok'      => $status
        ]);
    }

    private function formatLine($line)
    {
        $line = preg_replace("/\r\n|\r|\n/", '<br/>', $line);

        return $line;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->cms->get($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu  = $this->cms->all();
        $title = $id;
        $data  = $this->cms->get($id);
        $edit  = true;

        return view('cms::page', compact('data', 'title', 'edit', 'menu'));
    }

}
