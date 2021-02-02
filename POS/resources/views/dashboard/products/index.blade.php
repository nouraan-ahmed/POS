@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.products')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ url('dashboard/index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.products')</li>
            </ol>
        </section>

        {{-- <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.products') <small>{{ $products->total() }}</small></h3>

                    <form action="{{ route('dashboard.products.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                @if (auth()->user()->hasPermission('create_products'))
                                    <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @endif
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($products->count() > 0)

                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.first_name')</th>
                                <th>@lang('site.last_name')</th>
                                <th>@lang('site.email')</th>
                                <th>@lang('site.image')</th>
                                <th>@lang('site.action')</th>
                            </tr>
                            </thead>
                            
                            <tbody>
                            @foreach ($products as $index=>$user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->first_name }}</td>
                                    <td>{{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><img src="{{ $user->image_path }}" style="width: 100px;" class="img-thumbnail" alt=""></td>
                                    <td>
                                        @if (auth()->user()->hasPermission('update_products'))
                                            <a href="{{ route('dashboard.products.edit', $user->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @endif
                                        @if (auth()->user()->hasPermission('delete_products'))
                                            <form action="{{ route('dashboard.products.destroy', $user->id) }}" method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        @endif
                                    </td>
                                </tr>
                            
                            @endforeach
                            </tbody>

                        </table><!-- end of table -->
                        
                        {{ $products->appends(request()->query())->links() }}
                        
                    @else
                        
                        <h2>@lang('site.no_data_found')</h2>
                        
                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content --> --}}
        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.products')<small>{{$products->total()}}</small> </h3>
                    <form action="{{route('dashboard.index')}}" method="get">
                        <div class="row">
                        <div class="col-md-4">
                        <input type="text" name="search" class="form-control" value="{{request()->search}}" placeholder="@lang('site.search')">
                        </div>
                        <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>@lang('site.search')</button>
                        @if(auth()->user()->hasPermission('products_create'))
                        <a href="{{url('dashboard/products/create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')</a>  
                        @else
                        <a class="btn btn-primary disabled"></i class="fa fa-plus">@lang('site.add')</a>  
                        @endif
                        </div>
                        </div>
                    </form>

    @if ($products->count() > 0)

    <table class="table table-hover">

        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.name')</th>
            <th>@lang('site.description')</th>
            <th>@lang('site.image')</th>
            <th>@lang('site.purchase_price')</th>
            <th>@lang('site.sale_price')</th>
            <th>@lang('site.stock')</th>
            <th>@lang('site.action')</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach ($products as $index=>$product)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $product->name }}</td>
            <td>{!! $product->description !!}</td>
            <td><img src="{{$product->image_path}}" style="height:50px" class="img-thumbnail" alt=""></td>
            <td>{{ $product->purchase_price }}</td>
            <td>{{ $product->sale_price }}</td>
            <td>{{ $product->stock }}</td>
            
            <td>
            @if(auth()->user()->hasPermission('products_update'))
            <a class="btn btn-info btn-sm" href="{{ route('dashboard.edit',$product->id ) }}"><i class="fa fa-edit"></i>@lang('site.edit')</a>
            @else
            <a class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i>@lang('site.edit')</a>            
            @endif
            @if(auth()->user()->hasPermission('products_delete'))
            <form action="{{route('dashboard.destroy',$product->id)}}" method="post" style="display:inline-block">
                {{csrf_field()}}
                {{method_field('delete')}}
                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i>@lang('site.delete')</button>
                </form>
            @else
            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i>@lang('site.delete')</button>
            @endif
        </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    {{$products->appends(request()->query())->links()}}

    @else
                        
        <h2>@lang('site.no_data_found')</h2>
                        
    @endif

                </div></div>
</section>

    </div><!-- end of content wrapper -->


@endsection