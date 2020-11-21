<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Categories;

use Illuminate\Http\Request;
use Session, Validator, File, Auth;

class CategoryController extends Controller
{
    private $uploadPath     = "uploads/category";
    private $modelname      = "App\Categories";
    private $localname      = "category";
    private $tablename      = "category";
    private $imageinputname = array('image');
    private $publishfield   = "is_published";

    private $route = array('create' => 'category.create',
        'edit' => 'category.edit',
        'index' => 'category.index',
        'show' => 'category.show',
        'store' => 'category.store',
        'update' => 'category.update',
        'destroy' => 'category.destroy',
        'publish' => 'category.publish',
        'unpublish' => 'category.unpublish'
    );
    private $view = array('create' => 'admin.category.create',
        'edit' => 'admin.category.edit',
        'index' => 'admin.category.index',
        'show' => 'admin.category.show');

    private $indexvariables = array(
        'title' => 'ALL category',
        'newitem' => 'NEW CATEGORY',
        'url' => 'admin/category',
        'urltomain' => 'category/'
    );

    private $createvariables = array(
        'title' => 'CREATE NEW CATEGORY',
        'newitem' => 'NEW CATEGORY',
    );

    private $showvariables = array(
        'title' => 'CATEGORY DETAILS',
        'seeall' => 'SEE ALL category',
    );

    private $saveSuccess    = 'The category was successfully saved.';
    private $deletionSuccess = 'Category Deleted Successfully';
    private $updationSuccess = 'Category updated Successfully';
    private $singlepostvar  = "Category";
    private $multipostvar   = "category";
    private $indexpagination = 10;

    private $validation_rules = array(
        'name' => 'required',
        'branch_id'    => 'required',
        'is_published'=> 'required',
    );

    private $update_validation_rules = array(
        'name' => 'required',
        'branch_id'    => 'required',
        'is_published'=> 'required',
    );

    private $formfields = array(
        'name' => array('name'  =>  'name',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Album Name',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'text',
            'default' => null,
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'name',
                'placeholder' => 'Enter category name here',
                'required' => ''
            )
        ),
        'branch_id'=> array('name'  =>  'branch_id',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Choose Branch',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'select',
            'default' => null,
            'choices' => array(),
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'branch_id',
                'placeholder' => 'Choose Branch here.',
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
        'branch_id'  => array('label' => 'Belongs to Branch'),
        'name'  => array('label' => 'Name' ),
        'updated_at'=> array('label' => 'Updated At'),
    );




    private $showfields = array(
        'id' => array('label' => '#'),
        'branch_id'  => array('label' => 'Belongs to Branch'),
        'name'  => array('label' => 'Name')
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

        foreach($posts as &$post)
        {
            $branch = Branch::find($post->branch_id);
            $post->branch_id = $branch->name;
        }

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
        $branchs = Branch::all();
        $fields['branch_id']['choices'] = array();
        foreach($branchs as $branch)
        {
            $fields['branch_id']['choices'][$branch->id] = $branch->name;
        }
        return view($this->view['create'])->with('fields', $fields)
            ->with('route', $this->route)
            ->with('createvar', $this->createvariables);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createByBranch($branch_id)
    {
        // $timenow = time();
        $fields = $this->formfields;
        $branchs = Branch::all();
        $fields['branch_id']['choices'] = array();
        foreach($branchs as $branch)
        {
            $fields['branch_id']['choices'][$branch->id] = $branch->name;
        }
        $fields['branch_id']['default'] = $branch_id;
        return view($this->view['create'])->with('fields', $fields)
            ->with('route', $this->route)
            ->with('createvar', $this->createvariables);
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

        if(!empty($this->imageinputname))
        {

            $imginpnames = $this->imageinputname;

            foreach ($imginpnames as $imginpname)
            {

                if($request->hasFile($imginpname))
                {
                    if ($request->file($imginpname)->isValid())
                    {

                        $imageName = time().rand(5000,10000).'.'.$request->$imginpname->getClientOriginalExtension();

                        $request->$imginpname->move($this->uploadPath, $imageName);
                        $pathToImage = $this->uploadPath.'/'.$imageName;

                        $post->$imginpname = $this->uploadPath.'/'.$imageName;
                    }
                    else
                    {
                        Session::flash('warning', 'Uploaded file is not valid');
                        return redirect()->route($this->route['create'])
                            ->withErrors($validator)
                            ->withInput();
                    }
                }

            }

        }


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
        if($post)
        {
            $branch = Branch::find($post->branch_id);
            $post->branch_id = $branch->name;
            $post->branch_aid = $branch->id;
            return view($this->view['show'])->with('fields', $fields)
                ->with('route', $this->route)
                ->with($this->singlepostvar, $post)
                ->with('singlepostvar', $this->singlepostvar)
                ->with('showvar', $this->showvariables)
                ->with('uploadPath',url($this->uploadPath));
        }else{
            abort(404, 'Page not found.');
        }
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

        $branchs = Branch::all();
        $fields['branch_id']['choices'] = array();
        foreach($branchs as $branch)
        {
            $fields['branch_id']['choices'][$branch->id] = $branch->name;
        }

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
        $updaterules['slug'] = array('alpha_dash', Rule::unique('category')->ignore($id));


        $this->validate($request, $updaterules);

        if(!empty($this->imageinputname))
        {

            $imginpnames = $this->imageinputname;

            foreach ($imginpnames as $imginpname)
            {

                if($request->hasFile($imginpname))
                {
                    if ($request->file($imginpname)->isValid())
                    {
                        File::delete($post->imginpname);
                        $imageName = time().rand(5000,10000).'.'.$request->$imginpname->getClientOriginalExtension();
                        $request->$imginpname->move($this->uploadPath, $imageName);
                        $pathToImage = $this->uploadPath.'/'.$imageName;

                        GlideImage::create($pathToImage)
                            ->modify(['w'=> 500, 'h' => 350])
                            ->save($pathToImage);

                        $post->$imginpname = $this->uploadPath.'/'.$imageName;
                    }
                    else
                    {
                        Session::flash('warning', 'Uploaded file is not valid');
                        return back()->withErrors($validator)->withInput();
                    }
                }

            }

        }

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
        return redirect()->back();
    }


    public function unpublish($id)
    {
        $publishfield = $this->publishfield;
        $post = $this->modelname::find($id);
        $post->$publishfield = 0;
        $post->save();
        return redirect()->back();
    }
}
