<div class="col-md-3 col-lg-3" id="sidebar-collapse">
    <div class="list-group sidebar" style="font-size: 18px;">
        <a href="{{route('branch.index')}}" class="list-group-item list-group-item-primary
              {{Route::is('branch.index') ||
                Route::is('branch.show')||
                Route::is('branch.edit') ||
                Route::is('branch.create')? "active": ""}}">
            <i class="fa fa-clone"></i> Branch
        </a>
        <a href="{{route('category.index')}}" class="list-group-item list-group-item-primary
              {{Route::is('category.index') ||
                Route::is('category.show')||
                Route::is('category.edit') ||
                Route::is('category.create')? "active": ""}}">
            <i class="fa fa-book"></i> Cateogry
        </a>
        <a href="{{route('type.index')}}" class="list-group-item list-group-item-primary
              {{Route::is('type.index') ||
                Route::is('type.show')||
                Route::is('type.edit') ||
                Route::is('type.create')? "active": ""}}">
            <i class="fa fa-tags"></i> Type
        </a>
        <a href="{{route('post.index')}}" class="list-group-item list-group-item-primary
              {{Route::is('post.index') ||
                Route::is('post.show')||
                Route::is('post.edit') ||
                Route::is('post.create')? "active": ""}}">
            <i class="fa fa-sticky-note"></i> Post
        </a>
    </div>
</div>
