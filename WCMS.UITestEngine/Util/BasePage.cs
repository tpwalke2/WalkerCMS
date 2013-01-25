using System;
using OpenQA.Selenium;

namespace WCMS.UITestEngine.Util
{
  public abstract class BasePage
  {
    protected const string CURRENTLY_SELECTED_CLASS = "currently-selected";
    protected const string UNSELECTED_CLASS = "unselected";
    protected const string HEADER_SELECTOR = "div#header";
    protected const string HEADER_TITLE_LINK_SELECTOR = HEADER_SELECTOR + " a.homeLink";
    protected const string HEADER_SLOGAN_SELECTOR = HEADER_SELECTOR + " h2.slogan";
    protected const string NAV_ITEM_SELECTOR_FORMAT = "li#nav_{0}";
    protected const string NAV_ITEM_SELECTION_CSS_SELECTOR_FORMAT = NAV_ITEM_SELECTOR_FORMAT + ".{1}";
    protected const string PAGE_CONTENT_SELECTOR_FORMAT = "div#content.content-{0}";
    protected const string FOOTER_SELECTOR = "div#footer";
    protected const string FOOTER_COPYRIGHT_SELECTOR = FOOTER_SELECTOR + " span.copyright";
    protected const string FOOTER_HOME_LINK_SELECTOR = FOOTER_SELECTOR + " a.homeLink";
    protected const string FOOTER_XHTML_VALIDATOR_LINK_SELECTOR = FOOTER_SELECTOR + " a#validateXHTML";
    protected const string MISSING_NAV_ITEM_FORMAT = "Item with ID '{0}' was not found.";
    protected const string MISSING_CONTENT_FORMAT = "Content for section '{0}' was not found.";

    protected string _pageID;
    protected string _parentPageID;

    public BasePage(string pageID, string parentID)
    {
      _pageID = pageID;
      _parentPageID = parentID;
      WaitForCurrentlySelectedNav(_parentPageID);
    }

    public bool IsNavItemCurrentlySelected(string navSection)
    {
      IWebElement navItem = GetNavItem(navSection);
      return navItem.GetAttribute("class").Contains(SelectedClass);
    }

    public bool IsNavItemCurrentlyUnselected(string navSection)
    {
      IWebElement navItem = GetNavItem(navSection);
      return navItem.GetAttribute("class").Contains(UnselectedClass);
    }

    public string GetContent(string navSection)
    {
      return FindElementByCSS(GetContentSelector(navSection)).Text;
    }

    public string GetCurrentContent()
    {
      return GetContent(_pageID);
    }

    public string GetNavItemTitle(string navSection)
    {
      return GetNavItemLink(navSection).GetAttribute("title");
    }

    public string GetSlogan()
    {
      return FindElementByCSS(HEADER_SLOGAN_SELECTOR).Text;
    }

    public string GetHeaderTitle()
    {
      return FindElementByCSS(HEADER_TITLE_LINK_SELECTOR).Text;
    }

    public string GetFooterValidationLinkText()
    {
      return FindElementByCSS(FOOTER_XHTML_VALIDATOR_LINK_SELECTOR).Text;
    }

    public bool IsParentNavItemCurrentlySelected()
    {
      return this.IsNavItemCurrentlySelected(_parentPageID);
    }

    public string GetFooterCopyright()
    {
      return FindElementByCSS(FOOTER_COPYRIGHT_SELECTOR).Text;
    }

    public string GetFooterHomeLinkText()
    {
      return FindElementByCSS(FOOTER_HOME_LINK_SELECTOR).Text;
    }

    protected IWebElement FindElementByCSS(string cssSelector)
    {
      return Common.Driver.FindElement(By.CssSelector(cssSelector));
    }

    public string GetTitle()
    {
      return Common.Driver.Title;
    }

    protected T GoToNavLink<T>(string navItemSelector)
      where T : BasePage, new()
    {
      GetNavItemLink(navItemSelector).Click();
      return new T();
    }

    protected IWebElement GetNavItem(string navSection)
    {
      return FindElementByCSS(GetNavItemSelector(navSection));
    }

    protected IWebElement GetNavItemLink(string navSection)
    {
      IWebElement parentNavItem = GetNavItem(navSection);
      return parentNavItem.FindElement(By.TagName("a"));
    }

    protected void WaitForCurrentlySelectedNav(string navSection)
    {
      var navItem = Common.Driver.FindElement(By.CssSelector(GetSelectedNavItemSelector(navSection)), 10);
    }

    protected string SelectedClass
    {
      get { return CURRENTLY_SELECTED_CLASS; }
    }

    protected string UnselectedClass
    {
      get { return UNSELECTED_CLASS; }
    }

    protected string GetContentSelector(string navSection)
    {
      return string.Format(PAGE_CONTENT_SELECTOR_FORMAT, navSection);
    }

    protected string GetNavItemSelector(string navSection)
    {
      return string.Format(NAV_ITEM_SELECTOR_FORMAT, navSection);
    }

    protected string GetSelectedNavItemSelector(string navSection)
    {
      return String.Format(NAV_ITEM_SELECTION_CSS_SELECTOR_FORMAT, navSection, SelectedClass);
    }

    protected T GoToFooterHomeLink<T>()
      where T : BasePage, new()
    {
      FindElementByCSS(FOOTER_HOME_LINK_SELECTOR).Click();
      return new T();
    }

    protected T GoToHeaderHomeLink<T>()
      where T : BasePage, new()
    {
      FindElementByCSS(HEADER_TITLE_LINK_SELECTOR).Click();
      return new T();
    }
  }
}
