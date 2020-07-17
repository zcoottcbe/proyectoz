              <form method="post" action="{{ route("taquilla.buscar") }}" class="form-inline">
                @csrf
                <div class="btn-group" role="group">
                  <label class="sr-only" for="Buscar">Buscar</label>
                  <input type="buscar" class="form-control" name="buscar" id="buscar">
                  <button type="submit" class="btn btn-success">Buscar </button>
                </div>
              </form>

