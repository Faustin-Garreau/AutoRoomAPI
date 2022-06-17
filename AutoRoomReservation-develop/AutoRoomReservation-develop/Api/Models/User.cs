using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Text;
using System.Text.Json.Serialization;
using System.Threading.Tasks;

namespace Models
{
    public class User
    {
        [Key]
        public string Id { get; set; }

        [Column("firstName"), JsonPropertyName("firstname")]
        public string FirstName { get; set; }

        [Column("lastName"), JsonPropertyName("lastname")]
        public string LastName { get; set; }

        [Column("email"), JsonPropertyName("email")]
        public string Email { get; set; }

        [Column("password"), JsonPropertyName("password")]
        public string Password { get; set; }

        [Column("phone"), JsonPropertyName("phone")]
        public string Phone { get; set; }

        [Column("birthDate")]
        public DateTime BirthDate { get; set; } = DateTime.Now;

        [Column("nationality"), JsonPropertyName("nationality")]
        public string Nationality { get; set; }

        [Column("admin"), JsonPropertyName("admin")]
        public bool? Admin { get; set; } = false;

    }
}
