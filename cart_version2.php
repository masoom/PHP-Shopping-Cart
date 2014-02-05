/** 
  * Shopping Cart - Version 2
  * Changed getTotalSum() to accept dynamic values
  * from user instead of static hard coded Discounted_Price and Disc_on_Quantity values
  * if($product_count >= $Disc_on_Quantity) then calculate the final sum
*/

<?php
	session_start();
	

	interface CartInterface {
	    
	    public function getTotalSum();
	    public function addItem(Product $product, $amount);
	    public function getPriceOf(Product $product);
	    
	}
	class Product implements CartInterface
	{
		function getPriceOf(Product $product)
		{
			$result=mysql_query("select price from products where id=$product");
			// Returns Price form table products with id = $product
			$row=mysql_fetch_array($result);
			return $row['price'];
		}
		
		
		function addItem(Product $product, $amount)
		{
			if($product<1 or $amount<1) return;
			if(is_array($_SESSION['cart']))
			{
			if(product_exists($product)) return;
			$max=count($_SESSION['cart']);
			$_SESSION['cart'][$max]['productid']=$product;
			$_SESSION['cart'][$max]['qty']=$amount;
			}
			else
			
			{
			$_SESSION['cart']=array();
			$_SESSION['cart'][0]['productid']=$product;
			$_SESSION['cart'][0]['qty']=$amount;
			}
		}
		
		function getTotalSum() 
		{
				 $Discounted_Price=$_REQUEST['discount']; 
				 // Discounted_Price as a user input from admin dashboard. For example - Volume Discounted price of 0.05 
				 $Disc_on_Quantity=$_REQUEST['qty']; 
				 // Quantity as a user input from admin dashboard. For example - Volume Bulk Discount only when lemon quantity >10
			$max=count($_SESSION['cart']);
			$sum=0;
			$product_count=0;
				
				/* Replaced traditional For loop with Foreach for performance
				 for($i=0;$i<$max;$i++){
					$product=$_SESSION['cart'][$i]['productid'];
				$amount=$_SESSION['cart'][$i]['qty'];
				$product_count++;
				$price=get_price($product);
				$sum+=$price*$amount; */
				
				$array_loop[]=$_SESSION['cart'];
				foreach($array_loop as $array_loop=>$item)
				
				{
					$product=$item['productid'];
					$amount=$item['qty'];
				$product_count++;
				$price=get_price($product);
				$sum+=$price*$amount;
			}
			
			
			if($product_count >= $Disc_on_Quantity)
			{
				$sum-=$product_count * $Discounted_Price;
			}
		return $sum;
		}
	} 
	
	?>