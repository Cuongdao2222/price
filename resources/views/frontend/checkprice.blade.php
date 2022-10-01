
<form  method="get" action="{{ route('search-model') }}">
    <label for="fname">Nhập tên model:</label><br>
    <input type="text" id="fname" name="model"><br>
    <br>
    <button type="submit">submit</button>
  
</form> 
<div style="display:block; padding: 0 45px; width: 68% ;" id="content_1">
                            
    <table cellpadding="5" id="tb_padding" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse;">
        <tbody>
            <tr bgcolor="#EEEEEE" style="font-weight:bold;">
                <td>STT</td>
                <td>Site</td>
                <td>Tên sản phẩm</td>
                <td>Giá</td>

            </tr>
            @if(isset($info))                                     
            @foreach($info as $key=>$value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value['name_url'] }}</td>
                <td>{{ $value['name'] }}</td>
                <td>{!! $value['price'] !!}</td>
            </tr>
            @endforeach
            @endif
               
        </tbody>
    </table>    

  
</div>