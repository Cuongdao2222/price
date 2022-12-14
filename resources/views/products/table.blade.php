<div class="table-responsive">
    <table class="table" id="products-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Content</th>
        <th>Images</th>
        <th>Quatity</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $products)
            <tr>
                <td>{{ $products->name }}</td>
            <td>{{ $products->price }}</td>
            <td>{{ $products->category }}</td>
            <td>{!! $products->content !!}</td>
            <td><img src="{{ url($products->images) }}" style="width:50px"></td>
            <td>{{ $products->quatity }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['products.destroy', $products->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('products.show', [$products->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('products.edit', [$products->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
