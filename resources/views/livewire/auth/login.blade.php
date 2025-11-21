<div
    class="flex justify-center items-center bg-bg-primary text-text-primary min-h-screen transition-colors px-2 sm:p-0">

    <div
        class="absolute top-4 right-4 border border-custom hover:bg-bg-secondary rounded-sm cursor-pointer">
        <livewire:layout.theme-toggle/>
    </div>

    <div
        class="flex min-h-full w-full sm:max-w-md flex-col justify-center px-6 py-12 lg:px-8  rounded border border-custom">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=cyan&shade=500"
                 alt="Your Company"
                 class="mx-auto h-10 w-auto"/>

            <h2 class="mt-10 text-center text-2xl font-bold tracking-tight">
                Sign in to your account
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

            <form wire:submit.prevent="login" class="space-y-6">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium ">Email address</label>
                    <div class="mt-2">
                        <input id="email" type="email" name="email" required autocomplete="email"
                               wire:model.defer="email"
                               class="block w-full rounded-md bg-bg-secondary  border border-custom px-3 py-1.5 text-base
                                      placeholder:/50
                                      focus:border-green-600 outline-none"/>
                    </div>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium ">Password</label>
                        <div class="text-sm">
                            <a href="#" class="text-green-500 text-xs">
                                Forgot password?
                            </a>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               wire:model.defer="password"
                               class="block w-full rounded-md bg-bg-secondary  border border-custom px-3 py-1.5 text-base
                                      placeholder:/50
                                      focus:border-green-600 outline-none"/>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button -->
                <div>
                    <button type="submit"
                            wire:click="login"
                            class=" w-full btn btn-primary">
                        <svg fill="#ffffff" wire:loading wire:target="login"
                             class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                             xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                            </g>
                        </svg>

                        <span>Sign In</span>
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <p class="mt-10 text-center text-sm /70">
                Not a member?
                <a href="#">
                    Create a new account!
                </a>
            </p>

        </div>
    </div>
</div>
