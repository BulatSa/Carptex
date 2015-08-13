<div class="box homecategory">
	<?php foreach ($categories as $category) { ?> 
		<div class="home-category">
			<?php if ($category['image']) { ?> 
				<div class="image"><img src="<?php echo $category['image']; ?>"></div>
			<?php } ?> 
			<div class="cats">
				<div class="heading"><?php echo $category['name']; ?></div>
				<?php if ( !count($category['children']) && count($category['specials']) < 2 ) { ?> 
					<div class="article"><?php echo $category['description']; ?></div>
				<?php } ?> 
				<?php foreach ($category['children'] as $key => $child) { ?> 
					<?php if ($key < 3) { ?> 
						<div class="cat"><a href="<?php echo $child['href']; ?>"><?php if ($child['image']) { ?><img src="<?php echo $child['image']; ?>"></a><br><?php } ?><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></div>
					<?php } ?>
				<?php } ?>
				<?php if (count($category['specials']) > 1) { ?>
					<ul class="special">
						<?php foreach ($category['specials'] as $special) { ?> 
							<li>
								<?php if ($special['image']) { ?> 
									<a href="<?php echo $special['href'] ?>"><img src="<?php echo $special['image']; ?>"></a>
								<?php } ?>
								<a href="<?php echo $special['href'] ?>"><?php echo $special['name']; ?></a> <br> 
								<?php if ($special['special']) { ?> 
									<ins><?php echo $special['special']; ?></ins> <del><?php echo $special['price']; ?></del>
								<?php }  else { ?> 
									<ins><?php echo $special['price']; ?></ins>
								<?php } ?> 
							</li>
						<?php } ?>
					</ul>
				<?php } elseif ( count($category['specials']) == 1 && !count($category['children']) ) { ?> 
					<ul class="special">
						<li>
							<?php if ($category['specials'][0]['image']) { ?> 
								<a href="<?php echo $category['specials'][0]['href'] ?>"><img src="<?php echo $category['specials'][0]['image']; ?>"></a>
							<?php } ?>
							<a href="<?php echo $category['specials'][0]['href'] ?>"><?php echo $category['specials'][0]['name']; ?></a> <br> 
							<?php if ($category['specials'][0]['special']) { ?> 
								<ins><?php echo $category['specials'][0]['special']; ?></ins> <del><?php echo $category['specials'][0]['price']; ?></del>
							<?php }  else { ?> 
								<ins><?php echo $category['specials'][0]['price']; ?></ins>
							<?php } ?> 
						</li>
					</ul>
				<?php } ?> 
			</div>
			<?php if (count($category['children']) && count($category['specials']) == 1) { ?>
				<div class="special-force"></div>
				<div class="special">
					<a href="<?php echo $category['specials'][0]['href'] ?>"><?php echo $category['specials'][0]['name']; ?></a> <br>
					<?php if ($category['specials'][0]['special']) { ?> 
						<del><?php echo $category['specials'][0]['price']; ?></del> <ins><?php echo $category['specials'][0]['special']; ?></ins>
					<?php }  else { ?> 
						<ins><?php echo $category['specials'][0]['price']; ?></ins>
					<?php } ?> 
				</div>
			<?php } ?> 
		</div>
	<?php } ?>
</div>
