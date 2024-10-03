@section('title', $post->subject)
<x-app-layout>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">{{ config('app.name', 'Laravel') }}</a></li>
                <li class="breadcrumb-item"><a href="/app/forum">Forum</a></li>
                <li class="breadcrumb-item"><a href="/app/forum/{{ $category_id }}">{{ $category_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $post->subject }}</li>
            </ol>
        </nav>
        <x-card :title="'original post - ' . $post->subject">
            <div class="row">
                <div class="col-auto text-center">
                    <img src="/cdn/users/{{ $post->posterId }}?t={{ time() }}" alt="{{ $post->user->name }}" width="130" class="mb-1">
                    <p class="fw-medium mb-0">{{ $post->user->name }}</p>
                    <p class="text-muted fw-regular mb-2">{{ $postCount }} posts</p>

                    <button class="btn btn-success w-100" onclick="document.location = '/app/forum/reply/{{ $post->id }}'"><i class="far fa-reply mr-2"></i>reply</button>
                </div>
                <div class="col">
                    @if ($post->banned == 1) 
                    <p class="text-danger fw-regular mb-0" id="forum-parse">
                            <i>[ Post removed by moderation ]</i>
                    </p>
                        @else
                    <p class="text-muted fw-regular mb-0" id="forum-parse">
                        {{ $post->body }}
                    </p>
                        @endif
                    </p>
                </div>
            </div>
        </x-card>   

        @if ($replies->isEmpty()) 
            <div class="mt-3">
                <p class="text-center mb-0 text-muted">This post has no replies.</p>
            </div>
        @else

            <!--<div class="mt-3 d-flex justify-content-center w-100">
                {{ $replies->links() }}
            </div> FIXME-->

            @foreach ($repliesWithPostCount as $data)
            <div class="mt-3">
            <x-card :title="'Reply - ' .  $data['created_at'] " :bg="'header-purple'">
                <div class="row">
                    <div class="col-auto text-center">
                        <img src="/cdn/users/{{ $data['posterId'] }}?t={{ time() }}" alt="{{ $data['posterUsername'] }}" width="95" class="mb-1">
                        <p class="fw-medium mb-0">{{ $data['posterUsername'] }}</p>
                        <p class="text-muted fw-regular mb-0">{{ $data['userPostCount'] }} posts</p>
                    </div>
                    <div class="col">
                    @if ($data['banned'] == 1) 
                    <p class="text-danger fw-regular mb-0">
                            <i>[ Post removed by moderation ]</i>
                    </p>
                        @else
                    <p class="text-muted fw-regular mb-0" id="forum-reply-{{ $data['id'] }}">
                        {{ $data['reply'] }}
                    </p>
                        @endif
                    </div>
                </div>
            </x-card>
        </div>
            @endforeach
        @endif

    </div>

    <script src="/functions.js"></script>
    <script>
        parseForum()
    </script>
</x-app-layout>