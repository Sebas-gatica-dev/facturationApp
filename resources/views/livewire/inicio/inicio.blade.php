<div class="bg-gray-800 text-white py-8">
    <div class="container mx-auto text-center">
        <h1 class="text-5xl font-bold mb-6 text-white font-logo">Bank Analytics</h1>

        <div class="flex justify-around">

            <div>
                <div class="mb-2">
                    <span class="text-lg text-gray-400">Activos</span>
                </div>
                <div id="contadorActivos" class="text-4xl font-bold">0</div>
      

            </div>
          
            <div>
                <div class="mb-2">
                    <span class="text-lg text-gray-400">Inactivos</span>
                </div>
                <div id="contadorInactivos" class="text-4xl font-bold">0</div>

            </div>
           

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  $(document).ready(function() {
    animateCounter('#contadorActivos', {{$activos}});
    animateCounter('#contadorInactivos', {{$inactivos}});
  });

  function animateCounter(elementId, finalCount) {
    var duration = 2000; // La duración de la animación en milisegundos

    $({ countNum: $(elementId).text() }).animate({
      countNum: finalCount
    }, {
      duration: duration,
      easing: 'linear',
      step: function() {
        $(elementId).text(Math.floor(this.countNum));
      },
      complete: function() {
        $(elementId).text(finalCount);
      }
    });
  }
</script>
