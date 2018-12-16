<form style="margin:40px;" method="post" action="index.php?controller=utilisateur&action=created">
  <fieldset>
    <legend>Création de compte</legend>
	<p>
	  <label>E-mail</label> :
	  <input type="email" placeholder="Ex : jeanneymar@gmail.com" name="email" required />
	</p>
	<p>
      <label>Nom</label> :
      <input type="text" placeholder="Ex : Jean" name="nom" required />
    </p>
    <p>
      <label>Prenom</label> :
      <input type="text" placeholder="Ex : Neymar" name="prenom" required />
    </p>
	<p>
      <label>Mot de passe</label> :
      <input type="password" placeholder="Ex : 89aEG3F22LCbwjZV" name="mdp" required />
    </p>
	<p>
      <label>Confirmer mot de passe</label> :
      <input type="password" placeholder="Confirmer le mot de passe" name="mdpC" required />
    </p>
	<a href="index.php?controller=utilisateur&action=login">Déjà inscrit ? Connectez-vous !</a>
    <p>
      <input type="submit" value="Envoyer" />
    </p>
  </fieldset> 
</form>