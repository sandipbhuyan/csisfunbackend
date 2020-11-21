<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Branch;
use Session, Validator, File, Auth;

class BranchController extends Controller
{
    private $uploadPath     = "uploads/branch";
    private $modelname      = "App\Branch";
    private $localname      = "branch";
    private $tablename      = "branch";
    private $imageinputname = array('image');
    private $publishfield   = "is_published";

    private $route = array('create' => 'branch.create',
        'edit' => 'branch.edit',
        'index' => 'branch.index',
        'show' => 'branch.show',
        'store' => 'branch.store',
        'update' => 'branch.update',
        'destroy' => 'branch.destroy',
        'publish' => 'branch.publish',
        'unpublish' => 'branch.unpublish'
    );
    private $view = array('create' => 'admin.branch.create',
        'edit' => 'admin.branch.edit',
        'index' => 'admin.branch.index',
        'show' => 'admin.branch.show');

    private $indexvariables = array(
        'title' => 'ALL BRANCH',
        'newitem' => 'NEW BRANCH',
        'url' => 'admin/branch',
        'urltomain' => 'branch/'
    );

    private $createvariables = array(
        'title' => 'CREATE NEW BRANCH',
        'newitem' => 'NEW BRANCH',
    );

    private $showvariables = array(
        'title' => 'BRANCH DETAILS',
        'seeall' => 'SEE ALL BRANCH',
    );

    private $saveSuccess    = 'The branch was successfully saved.';
    private $deletionSuccess = 'Branch Deleted Successfully';
    private $updationSuccess = 'Branch updated Successfully';
    private $singlepostvar  = "branch";
    private $multipostvar   = "branch";
    private $indexpagination = 10;

    private $validation_rules = array(
        'name' => 'required',
        'is_published'=> 'required',
    );

    private $update_validation_rules = array(
        'name' => 'required',
        'is_published'=> 'required',
    );

    private $formfields = array(

        'name' => array('name'  =>  'name',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Branch Name',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'text',
            'default' => null,
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'name',
                'placeholder' => 'Enter branch name here',
                'required' => ''
            )
        ),
        'is_published'=> array('name'  =>  'is_published',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => ' Is Published ?',
            'field_icon' => 'fa fa-download',
            'type'  =>  'select',
            'default' => null,
            'choices' => array('0' => 'Save As Draft',
                '1' => 'Publish Now'),
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'is_published',
                'placeholder' => 'Do you want to publish it now?',
                'required' => ''
            )
        )
    );

    private $indexfields = array(
        'id' => array('label' => '#'),
        'name'  => array('label' => 'Name' ),
        'updated_at'=> array('label' => 'Updated At'),
    );




    private $showfields = array(
        'id' => array('label' => '#'),
        'name'  => array('label' => 'Name'),
    );


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->modelname::orderBy('id', 'desc')->paginate($this->indexpagination);
        $fields = $this->indexfields;

        return view($this->view['index'])->with($this->multipostvar, $posts)
            ->with('fields', $fields)
            ->with('multipostvar', $this->multipostvar)
            ->with('route', $this->route)
            ->with('indexvar', $this->indexvariables)
            ->with('uploadPath',url($this->uploadPath));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $timenow = time();
        $fields = $this->formfields;

        return view($this->view['create'])->with('fields', $fields)
            ->with('route', $this->route)
            ->with('createvar', $this->createvariables);
    }

    public function processFile($post, Request $request, $update = 0)
    {
        if(!empty($this->imageinputname))
        {

            $imginpnames = $this->imageinputname;

            foreach ($imginpnames as $imginpname)
            {

                if($request->hasFile($imginpname))
                {
                    if ($request->file($imginpname)->isValid())
                    {
                        if($update)
                        {
                            File::delete($post->imginpname);
                        }
                        $imageName = time().rand(5000,10000).'.'.$request->$imginpname->getClientOriginalExtension();
                        $request->$imginpname->move($this->uploadPath, $imageName);
                        $post->$imginpname = $this->uploadPath.'/'.$imageName;

                        $pathToImage = $post->$imginpname = $this->uploadPath.'/'.$imageName;
                    }
                    else
                    {
                        Session::flash('warning', 'Uploaded file is not valid');
                        return back()->withErrors($validator)->withInput();
                    }
                }

            }

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageName = null;
        $this->validate($request, $this->validation_rules);

        $post = new $this->modelname;


        $this->processFile($post,$request);

        foreach ($this->formfields as $fieldname => $fieldvalue) {
            if(!in_array($fieldname, $this->imageinputname))
                $post->$fieldname = $request->$fieldname;
        }

        $post->save();

        Session::flash('success', $this->saveSuccess);

        return redirect()->route($this->route['show'], $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fields = $this->showfields;
        $post = $this->modelname::find($id);

        $category = Categories::where('branch_id', $id)->paginate(5);

        return view($this->view['show'])->with('category', $category)
            ->with('fields', $fields)
            ->with('route', $this->route)
            ->with($this->singlepostvar, $post)
            ->with('singlepostvar', $this->singlepostvar)
            ->with('showvar', $this->showvariables)
            ->with('uploadPath',url($this->uploadPath));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fields = $this->formfields;
        $post = $this->modelname::find($id);
        return view($this->view['edit'])->with($this->singlepostvar ,$post)
            ->with('route', $this->route)
            ->with('fields', $fields)
            ->with('singlepostvar', $this->singlepostvar)
            ->with('uploadPath',url($this->uploadPath));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $imageName = null;
        $post = $this->modelname::find($id);

        $updaterules = $this->update_validation_rules;
        $updaterules['slug'] = array('alpha_dash', Rule::unique('branch')->ignore($id));


        $this->validate($request, $updaterules);

        $this->processFile($post,$request, 1);

        foreach ($this->formfields as $fieldname => $fieldvalue) {
            if(!in_array($fieldname, $this->imageinputname))
                $post->$fieldname = $request->$fieldname;
        }
        $post->save();

        Session::flash('success', $this->updationSuccess);

        return redirect()->route($this->route['show'], $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->modelname::find($id);
        if(!empty($this->imageinputname))
        {
            $inpname = $this->imageinputname;
            foreach ($inpname as $img) {
                File::delete($post->$img);
            }

        }
        $post->delete();
        Session::flash('success', $this->deletionSuccess);
        return redirect()->route($this->route['index']);
    }


    public function publish($id)
    {
        $publishfield = $this->publishfield;
        $post = $this->modelname::find($id);
        $post->$publishfield = 1;
        $post->save();
        return redirect()->route($this->route['index']);
    }


    public function unpublish($id)
    {
        $publishfield = $this->publishfield;
        $post = $this->modelname::find($id);
        $post->$publishfield = 0;
        $post->save();
        return redirect()->route($this->route['index']);
    }
}
