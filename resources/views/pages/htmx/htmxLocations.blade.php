<div class="dropdown locationDropdown{{$index}}"
     style=" position: absolute;top:150px ;left: 49.5%; transform: translateX(-49.5%);z-index: 100000;   width: 498px; border: 1px solid #ccc; background: #fff; max-height: 500px; overflow-y: auto;">

    {{--    @dd($locations)--}}

    <p>{{$index}}</p>
    @foreach($locations as $locationindex=> $location)
        <p class="location-item2"
           onclick="
        const id={{$location->id}};
        const name='{{$location->name}}';
        let select=document.querySelector('.locationSelect{{$index}}')
        let options= select.querySelectorAll('.options2')
        options.forEach((item)=>{
                if(item.value==id){
                    item.selected = true;
                    document.getElementById('locationSearch{{$index}}').value=name
                }
        })
        document.querySelector('.locationDropdown{{$index}}').style.display='none'
        "
        >{{$location->name}}</p>
    @endforeach
</div>