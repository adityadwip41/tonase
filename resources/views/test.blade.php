<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="input-group mb-2">
                        <input class="form-control" type="number" maxlength="7" id="number" aria-label="Example select with button addon" placeholder="Input ID Container 7 character numeric">   
                        <button class="btn btn-outline-secondary" onclick="create();" type="button">Button</button>
                      </div>

                    <div class="mb-2">
                        <label for="">Hasil : <span id="hasil"></span> </label>
                    </div>
                    <div class="card rounded shadow">
                        <div class="card-header">List Container</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>id</th>
                                        <th>Position</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kapal as $item)
                                    <tr class="text-center">
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->position}}</td>
                                        <td class="text-center">
                                            <a href="{{route('delete', $item->id)}}" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        let number;
        function create(){
            number = $('#number').val();
            let SendData = {
                number:number,
                _token:"{{ csrf_token() }}"
            }
            console.log(SendData);
            if(number.length == 7 && number > 0){
                $.ajax({
                    url: "{{ route('create') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: JSON.stringify(SendData),
                    contentType: 'application/json',
                    success: function(result) {
                        console.log(result)
                        let hasil = $('#hasil').html(result.hasil);
                        location.reload();
                    },
                });
            }else if(number == ''){
                $.ajax({
                    url: "{{ route('create') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: JSON.stringify(SendData),
                    contentType: 'application/json',
                    success: function(result) {
                        console.log(result)
                        let hasil = $('#hasil').html(result.hasil);
                        location.reload();
                    },
                });
               
            }else{
                alert('id wajib 7 karakter atau dikosongkan untuk random!')
            }
            
        }
    </script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>