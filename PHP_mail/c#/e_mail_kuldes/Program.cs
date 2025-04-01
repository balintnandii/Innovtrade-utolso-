using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using System.Net;
using System.Net.Mail;

namespace e_mail_kuldes
{

   

    class Program
    {

        public void email_send()
        {
            MailMessage mail = new MailMessage();
            SmtpClient SmtpServer = new SmtpClient("smtp.gmail.com");
            mail.From = new MailAddress("lipak101@gmail.com");
            mail.To.Add("lipak101@gmail.com");
            mail.Subject = "Test Mail C#-ból";
            mail.Body = "A C#-ból küldött üzenet ";

            System.Net.Mail.Attachment attachment;

            attachment = new System.Net.Mail.Attachment("c:/xampp/htdocs/mail_uj/PHPMailer/sablon.txt");
            mail.Attachments.Add(attachment);

            SmtpServer.Port = 587;
            SmtpServer.Credentials = new System.Net.NetworkCredential("lipak101@gmail.com", "");
            SmtpServer.EnableSsl = true;

            try
            {
                SmtpServer.Send(mail);
                Console.WriteLine("Sikeres e-mail küldés!");
            }
            catch {
                Console.WriteLine("Hibás e-mail küldés!");
             }
        }


        static void Main(string[] args)
        {

            var mc = new Program();
            mc.email_send();
            Console.ReadKey();
           
        }
    }
}
