<?php

namespace Cswiley\Cms\API;

use App\Http\Controllers\Controller;
use Cswiley\Cms\Cms;

class CmsController extends Controller
{
    private $cms;

    public function __construct(Cms $cms)
    {
        $this->cms = $cms;
    }

    public function show($name)
    {
        $data = $this->cms->get($name);
        return response()->json($data);
    }
}
