# PowerShell script to update Product Gallery files with cart and buy functionality

$productUpdates = @(
    @{ File = "Product5_Gallery.html"; Name = "boAt Stone 1200"; Price = 3999 },
    @{ File = "Product6_Gallery.html"; Name = "UE Wonderboom 4"; Price = 6499 },
    @{ File = "Product7_Gallery.html"; Name = "UE MEGABOOM 3"; Price = 12999 },
    @{ File = "Product8_Gallery.html"; Name = "JBL Charge 5"; Price = 14999 }
)

$basePath = "d:\Freelancing\Bhavya\pages\"

foreach ($product in $productUpdates) {
    $filePath = Join-Path $basePath $product.File
    
    if (Test-Path $filePath) {
        Write-Host "Updating $($product.File)..."
        
        # Read the file content
        $content = Get-Content $filePath -Raw
        
        # Update the button HTML
        $oldButtons = '<div class="buttons">\s*<button class="cart">Add to Cart</button>\s*<button class="buy">Buy Now</button>\s*</div>'
        $newButtons = "<div class=`"buttons`">`n        <button class=`"cart`" onclick=`"addToCart('$($product.Name)', $($product.Price))`">Add to Cart</button>`n        <button class=`"buy`" onclick=`"buyNow('$($product.Name)', $($product.Price))`">Buy Now</button>`n      </div>"
        
        $content = [regex]::Replace($content, $oldButtons, $newButtons, [System.Text.RegularExpressions.RegexOptions]::Singleline)
        
        # Add JavaScript functions before closing </body> tag
        $jsFunctions = @"
<script>
// Add to cart function
function addToCart(productName, price) {
  // Get existing cart or create new one
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  
  // Check if product already exists in cart
  const existingItemIndex = cart.findIndex(item => item.name === productName);
  
  if (existingItemIndex > -1) {
    // Increase quantity if item already exists
    cart[existingItemIndex].quantity += 1;
  } else {
    // Add new item to cart
    cart.push({
      name: productName,
      price: price,
      quantity: 1
    });
  }
  
  // Save cart to localStorage
  localStorage.setItem('cart', JSON.stringify(cart));
  
  // Show confirmation message
  alert(`\${productName} added to cart!`);
}

// Buy now function
function buyNow(productName, price) {
  // Clear existing cart and add only this product
  const cart = [{
    name: productName,
    price: price,
    quantity: 1
  }];
  
  // Save to localStorage
  localStorage.setItem('cart', JSON.stringify(cart));
  
  // Redirect to checkout
  window.location.href = 'checkout.html';
}
</script>
"@
        
        # Insert JavaScript before </body>
        $content = $content -replace '</body>', "$jsFunctions`n</body>"
        
        # Write the updated content back to file
        Set-Content $filePath -Value $content -Encoding UTF8
        
        Write-Host "Successfully updated $($product.File)"
    } else {
        Write-Host "File not found: $filePath"
    }
}

Write-Host "All product gallery files have been updated!"