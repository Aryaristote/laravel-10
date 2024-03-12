<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Add or update avatar
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update or add your profile's avatar picture.") }}
        </p>
    </header>

    <img class="rounded-full" src="{{ "/storage/$user->avatar" }}" width="80" />

    @if (session('message'))
        <div class="text-red-500">
            {{ session('message') }}
            <div>
    @endif


    <form method="post" action="{{ route('profile.avatar') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="avatar" value="Avatar" />
            <x-text-input id="avatar" name="avatar" type="file" class="mt-1 block w-full" :value="old('avatar', $user->avatar)"
                autofocus autocomplete="avatar" />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>Update</x-primary-button>
        </div>
    </form>
</section>
