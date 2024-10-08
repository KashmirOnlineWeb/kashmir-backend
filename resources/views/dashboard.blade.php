<x-app-layout>
    <div class="flex items-center">
        <h1 class="text-lg font-semibold md:text-2xl">Dashboard</h1>
    </div>
    <div x-chunk="An empty state showing no products with a heading, description and a call to action to add a product."
        class="flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm">
        <div class="flex flex-col items-center gap-1 text-center">
            <h3 class="text-2xl font-bold tracking-tight">Coming Soon</h3>
            <p class="text-sm text-muted-foreground">This is blank space.
            </p><button
                class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 mt-4">Add
                Explore</button>
        </div>
    </div>
</x-app-layout>
