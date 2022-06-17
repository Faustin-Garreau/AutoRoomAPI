using Dapper;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Primitives;
using Models;
using MySql.Data.MySqlClient;
using System.Data;
using System.Text.Json;

namespace Api.Controllers
{
    [Route("[controller]/[action]")]
    [ApiController]
    public class RoomController : Controller
    {

        private readonly IConfiguration _config;

        private readonly string CS;

        private MySqlConnection Connection { get; set; }

        public RoomController(IConfiguration config)
        {
            _config = config;
            CS = _config.GetConnectionString("DB");
            Connection = new(CS);
        }

        [HttpGet]
        public string Get([FromQuery] string Id) 
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }

                if (string.IsNullOrWhiteSpace(Id))
                {
                    throw new Exception("L'id est null");
                }

                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var room = Connection.QuerySingle<Room>("room_get", param, commandType: CommandType.StoredProcedure);
                if (room == null)
                {
                    throw new Exception("Aucun chambre n'est associé à cette id");
                }
                //room.apartment = GetApartment(room.ApartmentId);

                return JsonSerializer.Serialize(new { Success = true, Content = room });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public string Search([FromQuery] string ville)
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }

                if (string.IsNullOrWhiteSpace(ville))
                {
                    throw new Exception("La ville est requise");
                }
                DynamicParameters param = new();
                param.Add("City", ville.ToLower().Trim());
                var rooms = Connection.Query<Room>("room_search", param, commandType: CommandType.StoredProcedure).ToList();
                if (!rooms.Any())
                {
                    throw new Exception("Aucune chambres dans cette ville");
                }
                /*rooms.ForEach(room => {
                    room.apartment = GetApartment(room.ApartmentId);
                });*/

                return JsonSerializer.Serialize(new { Success = true, Content = rooms });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public string GetAll()
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }

                var rooms = Connection.Query<Room?>("room_get_all", commandType: CommandType.StoredProcedure).ToList();
                if (!rooms.Any())
                {
                    throw new Exception("Aucun chambres");
                }
                rooms.ForEach(room => {
                    room.apartment = GetApartment(room.ApartmentId);
                });
                 
                return JsonSerializer.Serialize(new { Success = true, Content = rooms });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpPost]
        public async Task<string> Add()
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }

                StreamReader reader = new(Request.Body);
                var roomStr = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(roomStr))
                {
                    throw new Exception("La requete est vide");
                }
                var room = JsonSerializer.Deserialize<Room>(roomStr);
                if (room == null)
                {
                    throw new Exception("Les données sont vides ou malformé");
                }

                room.Id = Guid.NewGuid().ToString();
                DynamicParameters param = new();
                param.AddDynamicParams(room);
                Connection.Execute("room_insert", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public string Remove([FromQuery] string Id)
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }

                DynamicParameters param = new();
                param.Add(nameof(Id), Id);

                var row = Connection.Execute("room_delete", param, commandType: CommandType.StoredProcedure);
                
                if (row < 1)
                {
                    return JsonSerializer.Serialize(new { Success = false, Error = "Aucune chambre ne correspond à cette id" });
                }
                return JsonSerializer.Serialize(new { Success = true });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }
        
        [HttpPost]
        public async Task<string> Update()
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }

                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var room = JsonSerializer.Deserialize<Room>(str);
                if (room.Id == null)
                {
                    throw new Exception("L'id est vide");
                }
                
                DynamicParameters param = new();
                param.AddDynamicParams(room);
                Connection.Execute("room_update", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        private Apartment GetApartment(string Id)
        {
            DynamicParameters param = new();
            param.Add(nameof(Id), Id);
            var apartment = Connection.QuerySingle<Apartment>("apartment_get", param, commandType: CommandType.StoredProcedure);
            if (apartment == null)
            {
                throw new Exception("Aucun appartement n'est associé à cette chambre");
            }
            return apartment;
        }

        public bool IsAdmin([FromQuery] string Id)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.QuerySingle<User>("user_is_admin", param, commandType: CommandType.StoredProcedure);
                return user.Admin ?? false;
            }
            catch (Exception)
            {
                return false;
            }

        }

    }
}
