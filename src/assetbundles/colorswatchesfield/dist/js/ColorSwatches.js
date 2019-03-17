ColorSelectInput = Garnish.Base.extend( {

  $container: null,
  $options: null,
  $selectedOptions: [],
  $input: null,

  init: function( id ) {
    this.$container = $( '#' + id );
    this.$options = this.$container.find( '.option' );
    this.$selectedOptions = this.$options.filter( '.active' );
    this.$input = this.$container.next( 'input' );

    this.addListener( this.$options, 'click', 'onOptionSelect' );
  },

  onOptionSelect: function( ev ) {
    var $option = $( ev.currentTarget );

    if ( $option.hasClass( 'active' ) ) {
      $option.removeClass( 'active' );
    }
    else {
      if ( !this.$container.hasClass( 'js-multiple' ) ) {
        this.$options.removeClass( 'active' );
      }

      $option.addClass( 'active' );
    }

    this.$selectedOptions = this.$options.filter( '.active' );

    var values = [];

    this.$selectedOptions.each( function() {
      values.push( $( this ).data( 'value' ) )
    } )

    this.$input.val( JSON.stringify( values ) );
  }

} );