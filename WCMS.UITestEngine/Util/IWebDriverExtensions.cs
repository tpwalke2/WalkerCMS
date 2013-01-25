using System;
using NUnit.Framework;
using System.Threading;
using OpenQA.Selenium.Support.UI;

namespace OpenQA.Selenium
{
  public static class IWebDriverExtensions
  {
    public static IWebElement FindElement(this IWebDriver driver, By by, int timeoutInSeconds)
    {
      if (timeoutInSeconds > 0)
      {
        var wait = new WebDriverWait(driver, TimeSpan.FromSeconds(timeoutInSeconds));
        return wait.Until(drv => drv.FindElement(by));
      }
      return driver.FindElement(by);
    }
  }
}