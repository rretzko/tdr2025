@props([
    'postContent' => 'Post Content',
    'postTitle' => 'Post Title',
])
<div>
    <h1>{{ $postTitle }}</h1>

    <div x-data="{ expanded: false }">
        <button type="button" x-on:click="expanded = ! expanded">
            <span x-show="! expanded">Show post content...</span>
            <span x-show="expanded">Hide post content...</span>
        </button>

        <div x-show="expanded">
            {{ $postContent }}
        </div>
    </div>
</div>
