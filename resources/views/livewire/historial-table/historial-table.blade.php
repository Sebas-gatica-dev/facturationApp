<div>
    <h1>
      Historial Table
    </h1>
  
  
    <table>
      <thead>
        <tr>
          <th>Nro_Cliente</th>
          <th>Servicio</th>
          <th>Periodo</th>
          <th>Activos</th>
          <th>Inactivos</th>
          <th>Remito</th>
          <th>Modulo</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listaFacturaciones as $f)
          <tr>
              <td>{{ $f->cliente }}</td>
              <td>{{ $f->servicio }}</td>
              <td>{{ $f->periodo }}</td>
              <td>{{ $f->cantidad_activos }}</td>
              <td>{{ $f->cantidad_inactivos }}</td>
              <td>{{ $f->remito }}</td>
              <td>{{ $f->modulo }}</td>
  
          </tr>
        @endforeach
  
      </tbody>
  
    </table>
  </div>