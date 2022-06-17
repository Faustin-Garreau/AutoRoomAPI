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
    public class ApartmentController : Controller
    {

        private readonly IConfiguration _config;

        private readonly string CS;

        private MySqlConnection Connection { get; set; }

        public ApartmentController(IConfiguration config)
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
                var apartment = Connection.QuerySingle<Apartment>("apartment_get", param, commandType: CommandType.StoredProcedure);
                if (apartment == null)
                {
                    throw new Exception("Aucun appartement n'est associé à cette id");
                }
                apartment.Rooms = GetRooms(apartment.Id);
                
                return JsonSerializer.Serialize(new { Success = true, Content = apartment });
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
                var apartment = Connection.Query<Apartment?>("apartment_get_all", commandType: CommandType.StoredProcedure).ToList();
                if (!apartment.Any())
                {
                    throw new Exception("Aucun apartment");
                }
                apartment.ForEach(apart =>{
                    apart.Rooms = GetRooms(apart.Id);
                });
                return JsonSerializer.Serialize(new { Success = true, Content = apartment });
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
                var apartmentStr = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(apartmentStr))
                {
                    throw new Exception("La requete est vide");
                }
                var apartment = JsonSerializer.Deserialize<Apartment>(apartmentStr);
                
                apartment.Longitude = "05";
                apartment.Latitude = "50";

                if (apartment == null)
                {
                    throw new Exception("Les données sont vides ou malformé");
                }

                apartment.Id = Guid.NewGuid().ToString();
                DynamicParameters param = new();
                param.AddDynamicParams(apartment);
                Connection.Execute("apartment_insert", param, commandType: CommandType.StoredProcedure);
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
                
                Connection.Execute("room_delete_by_apart", param, commandType: CommandType.StoredProcedure);
                var row = Connection.Execute("apartment_delete", param, commandType: CommandType.StoredProcedure);
                
                if (row < 1)
                {
                    return JsonSerializer.Serialize(new { Success = false, Error = "Aucun appartement ne correspond à cette id" });
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
                var apartment = JsonSerializer.Deserialize<Apartment>(str);
                if (apartment.Id == null)
                {
                    throw new Exception("L'id est vide");
                }

                DynamicParameters param = new();
                param.AddDynamicParams(apartment);
                Connection.Execute("apartment_update", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }
    
        private List<Room> GetRooms(string ApartmentId)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(ApartmentId), ApartmentId);
                var rooms = Connection.Query<Room>("room_get_by_apart", param, commandType: CommandType.StoredProcedure).ToList();
                return rooms;
            }
            catch (Exception e)
            {
                return new();
            }
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
