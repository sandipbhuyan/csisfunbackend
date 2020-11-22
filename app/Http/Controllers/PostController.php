<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Type;
use Illuminate\Http\Request;
use Session, Validator, File, Auth;
use Storage;

class PostController extends Controller
{
    private $uploadPath     = "uploads/post";
    private $modelname      = "App\Post";
    private $imageinputname = array('content');
    private $publishfield   = "is_published";

    private $route = array('create' => 'post.create',
        'edit' => 'post.edit',
        'index' => 'post.index',
        'show' => 'post.show',
        'store' => 'post.store',
        'update' => 'post.update',
        'destroy' => 'post.destroy',
        'publish' => 'post.publish',
        'unpublish' => 'post.unpublish'
    );
    private $view = array('create' => 'admin.posts.create',
        'edit' => 'admin.posts.edit',
        'index' => 'admin.posts.index',
        'show' => 'admin.posts.show');

    private $indexvariables = array(
        'title' => 'ALL POST',
        'newitem' => 'NEW POST',
        'url' => '/post',
        'urltomain' => 'post/'
    );

    private $createvariables = array(
        'title' => 'CREATE NEW STORY',
        'newitem' => 'NEW STORY',
    );

    private $showvariables = array(
        'title' => 'POST DETAILS',
        'seeall' => 'SEE ALL POST',
    );

    private $saveSuccess    = 'The Post was successfully saved.';
    private $deletionSuccess = 'The User Story is Deleted Successfully';
    private $updationSuccess = 'The Post is updated Successfully';
    private $singlepostvar  = "post";
    private $multipostvar   = "post";
    private $indexpagination = 10;

    private $validation_rules = array(
        'header'      => 'required',
        'description' => 'required',
        'content' => 'required',
        'category_id' => 'required',
        'type_id' => 'required',
        'is_published'=> 'required',
    );

    private $update_validation_rules = array(
        'header'      => 'required',
        'description' => 'required',
        'content' => 'required',
        'category_id' => 'required',
        'type_id' => 'required',
        'is_published'=> 'required',
    );

    private $formfields = array(
        'header' => array('name'  =>  'header',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Header',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'text',
            'default' => null,
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'header',
                'placeholder' => 'Enter Header',
                'required' => ''
            )
        ),
        'description' => array('name'  =>  'description',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Description',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'textarea',
            'default' => null,
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'description',
                'placeholder' => 'Enter Description Here',
                'required' => '',
                'rows' => 5
            )
        ),
        'content' => array('name'  =>  'content',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Content',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'textarea',
            'default' => null,
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'header',
                'placeholder' => 'Enter Eligibility Here',
                'required' => '',
                'rows' => 30
            )
        ),
        'category_id'=> array('name'  =>  'category_id',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Choose Category',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'select',
            'default' => null,
            'choices' => array(),
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'branch_id',
                'placeholder' => 'Choose Category here.',
                'required' => ''
            )
        ),
        'type_id'=> array('name'  =>  'type_id',
            'label_length' => 'col-lg-4',
            'field_length' => 'col-lg-8',
            'label' => 'Choose Type',
            'field_icon' => 'fa fa-pencil',
            'type'  =>  'select',
            'default' => null,
            'choices' => array(),
            'extras'=> array('class' => 'form-control border-input',
                'id' => 'branch_id',
                'placeholder' => 'Choose Type here.',
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
        'header' => array('label' => 'Header'),
        'description' => array('label' => 'Description'),
        'category' => array('label' => 'Category'),
        'type' => array('label' => 'Type'),
        'views' => array('label' => 'Views'),
        'likes' => array('label' => 'Likes'),
        'updated_at'=> array('label' => 'Updated At'),
    );

    private $showfields = array(
        'id' => array('label' => '#'),
        'header' => array('label' => 'Header'),
        'description' => array('label' => 'Description'),
        'category' => array('label' => 'Category'),
        'type' => array('label' => 'Type'),
        'content' => array('label' => 'Content'),
        'views' => array('label' => 'Views'),
        'likes' => array('label' => 'Likes'),
        'updated_at'=> array('label' => 'Updated At'),
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
        $posts = $this->modelname::orderBy('id', 'desc')->where('uid', Auth::user()->id)->paginate($this->indexpagination);
        $fields = $this->indexfields;
        foreach($posts as $post)
        {
            $category = Categories::find($post->category_id);
            $post->category = $category->name;
            $type = Type::find($post->type_id);
            $post->type = $type->name;
        }
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
        $category = Categories::all();
        $type = Type::all();
        $fields['category_id']['choices'] = array();
        $fields['type_id']['choices'] = array();

        foreach ($category as $c)
        {
            $fields['category_id']['choices'][$c->id] = $c->name;
        }
        foreach ($type as $t)
        {
            $fields['type_id']['choices'][$t->id] = $t->name;
        }

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
                        if($imginpname == 'image')
                        {
                            $imageName = time().rand(5000,10000).'.'.$request->$imginpname->getClientOriginalExtension();
                            $request->$imginpname->move($this->uploadPath, $imageName);
                            $post->$imginpname = $this->uploadPath.'/'.$imageName;
                        }

                    }
                    else
                    {
                        Session::flash('warning', 'Uploaded file is not valid');
                        return redirect()->route($this->route['create'])
                            ->withErrors($validator)
                            ->withInput();
                    }
                }
                else
                {
                    $name = time().rand(5000,10000).'.md';
                    Storage::disk('general_uploads')->put($name,$request->$imginpname);
                    $post->$imginpname = $name;
                }

            }

        }


        foreach ($this->formfields as $fieldname => $fieldvalue) {
            if(!in_array($fieldname, $this->imageinputname))
                $post->$fieldname = $request->$fieldname;
        }
        $post->views = 0;
        $post->currentViews = 0;
        $post->likes = 0;
        $post->uid = Auth::user()->id;
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
        $post->content = Storage::disk('general_uploads')->get($post->content);

        $category = Categories::find($post->category_id);
        $post->category = $category->name;
        $type = Type::find($post->type_id);
        $post->type = $type->name;

        return view($this->view['show'])->with('fields', $fields)
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
        $post->content = Storage::disk('general_uploads')->get($post->content);

        $category = Categories::all();
        $type = Type::all();
        $fields['category_id']['choices'] = array();
        $fields['type_id']['choices'] = array();

        foreach ($category as $c)
        {
            $fields['category_id']['choices'][$c->id] = $c->name;
        }
        foreach ($type as $t)
        {
            $fields['type_id']['choices'][$t->id] = $t->name;
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
                        Storage::disk('general_uploads')->delete($post->imginpname);
                        $imageName = time().rand(5000,10000).'.'.$request->$imginpname->getClientOriginalExtension();
                        $request->$imginpname->move($this->uploadPath, $imageName);
                        $post->$imginpname = $this->uploadPath.'/'.$imageName;
                    }
                    else
                    {
                        Session::flash('warning', 'Uploaded file is not valid');
                        return back()->withErrors($validator)->withInput();
                    }
                }
                else
                {
                    Storage::disk('general_uploads')->delete($post->content);
                    $name = time().rand(5000,10000).'.md';
                    Storage::disk('general_uploads')->put($name,$request->$imginpname);
                    $post->$imginpname = $name;
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
                Storage::disk('general_uploads')->delete($post->$img);
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
