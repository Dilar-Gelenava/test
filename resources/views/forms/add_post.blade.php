<div class="col-md-8">
    <div class="card" style="background-color: transparent">
        <div class="card-header" style="background-color: purple;">
            <h3> What's on your mind, {{ Auth::user()->name }}? </h3>
            <button onclick="showForm()" id="showFormButton" class="btn btn-primary"> Create a post </button>
        </div>
        <div class="container" style="background-color: #646464; border-radius: 0 0 15px 15px">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div id="postForm" class="container col-lg-offset-4 col-lg-4" style="margin: 20px; display: none;">
                <form action="{{ route("storePost") }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" class="form-control" name="title" placeholder="title" style="width: auto;">
                    <br>
                    <textarea name="description" class="md-textarea form-control" placeholder="description" rows="3" style="width: auto;"></textarea>
                    <br>
                    <input type="file" name="image" style="width: auto;" accept="image/png, image/jpeg, video/mp4, audio/mp3">
                    <p>(you can add images, videos and audio files only!)</p>
                    <button class="btn btn-primary">Add Post</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function showForm() {
        document.getElementById("postForm").style.display = "block";
        document.getElementById("showFormButton").style.display = "none";
    }
</script>
