<div class="col-md-3 col-lg-3" id="sidebar-collapse">
    <div class="list-group sidebar" style="font-size: 18px;">

        <a href="{{route('branch.index')}}" class="list-group-item
              {{Route::is('branch.index') ||
                Route::is('branch.show')||
                Route::is('branch.edit') ||
                Route::is('branch.create')? "active": ""}}">
            <i class="fa fa-book"></i> Branch
        </a>
        <a href="{{route('category.index')}}" class="list-group-item
              {{Route::is('category.index') ||
                Route::is('category.show')||
                Route::is('category.edit') ||
                Route::is('category.create')? "active": ""}}">
            <i class="fa fa-book"></i> Cateogry
        </a>
    </div>
</div>
