using NUnit.Framework;
using OpenQA.Selenium;
using OpenQA.Selenium.Chrome;
using OpenQA.Selenium.Firefox;
using OpenQA.Selenium.IE;
using WCMS.UITestEngine.Util;

namespace WCMS.UITestEngine
{
  public abstract class BaseSetupFixture
  {
    protected void DefaultSetUp(string webBrowser)
    {
      Common.WebBrowser = webBrowser;
      Common.Driver = StartBrowser(Common.WebBrowser);
    }

    protected void DefaultTearDown()
    {
      Common.Driver.Quit();
    }

    protected IWebDriver StartBrowser(string webbrowser)
    {
      switch (webbrowser)
      {
        case "firefox":
          FirefoxProfile ffProfile = new FirefoxProfile();
          ffProfile.AcceptUntrustedCertificates = true;
          return new FirefoxDriver(ffProfile);
        case "iexplore":
          var ieOptions = new InternetExplorerOptions();
          ieOptions.IgnoreZoomLevel = true;
          ieOptions.IntroduceInstabilityByIgnoringProtectedModeSettings = true;
          return new InternetExplorerDriver(ieOptions);
        case "chrome":
          return new ChromeDriver();
      }

      return null;
    }
  }
}