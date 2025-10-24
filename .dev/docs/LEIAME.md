$cats = cache()->remember('categories', 3600, fn()=>Category::all());
