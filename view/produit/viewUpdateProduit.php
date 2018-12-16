<form style="margin:40px;" method="post" action="index.php?controller=produit&action=updated&numproduit=<?php echo $numproduit; ?>">
	<fieldset>
    <legend>Formulaire de modification d'un produit</legend>
	<p>
		<label for="nom_id">Nom :</label>
		<input type="text" placeholder="Ex : Samsung Galaxy S3" value="<?php echo $nomproduit;?>" name="nomP" id="nom_id" required />
	</p>
	<p>
		 <label for="prix_id">Prix :</label> 
		 <input type="number" placeholder="Ex : 400" value="<?php echo $prix;?>" min="0" step="0.01"  name="prix" id="prix_id" required />
	</p>
	<p> Description : (500 caractères max.)<br/>
		<textarea name="description" rows="5" cols="45" maxlength="500" placeholder="Ex : Téléphone 5 pouces de marque Samsung, disposant d'une connectivité 4G et d'un processeur quadcore..."><?php echo $description;?></textarea>
	</p>
	<p>
		 <label for="quantite_id">Quantité en stock :</label>
		 <input type="number" placeholder="Ex : 30" value="<?php echo $stock;?>" name="stock" min="0" id="quantite_id"  required/>
	</p>
	<p>
		<input style="margin-right:100px" type="submit" value="Envoyer" />
		<a href='index.php'>Retourner sur la page d'accueil sans enregistrer</a>
	</p>
	</fieldset> 
</form>
