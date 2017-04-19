# Monad för post och get values

```
$movieId = Post::get('movieId') // Returnerar Just(value) eller Nothing.
	->filter('isnumeric') // Tar en function
	->withDefault(1); // Få ut värdet eller default
```

* Använd Just & Nothing.
* Automatisk escape av input???

# Autoescape av output???
* Går det att få automatisk escape på output via view?
	* Regex på alla <?= value ?> -> <?=htmlenteties( value )?>
	* Overide?? <?=r() ?>
* Bara fixa med enkel funktion?
* Eller kanske wrappa all output i ett objekt med magiska klassen `__invoke()`???
	* Förmodligen överarbete.
* Implementera ett litet enkelt vy-språk, {{ $var }} {% if (expr) %} {% else if (expr) %} {% endif %}, {% foreach %} {% endforeach %}.
	* {{ $var }} == <?= htmlenteties( $var ) ?>
	* {% expression %} == <?php expression ?>